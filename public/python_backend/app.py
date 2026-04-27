import os
import json
import time
import logging
from typing import Any, Dict, List

import requests
from flask import Flask, jsonify, request
from dotenv import load_dotenv

BASE_DIR = os.path.dirname(__file__)
ROOT_ENV = os.path.abspath(os.path.join(BASE_DIR, "..", "..", ".env"))
load_dotenv(ROOT_ENV)
load_dotenv(os.path.join(BASE_DIR, ".env"))

app = Flask(__name__)

GEMINI_API_KEY = os.getenv("GEMINI_API_KEY", "")
BACKEND_TOKEN = os.getenv("BACKEND_TOKEN", "")
GEMINI_BASE_URL = os.getenv("GEMINI_BASE_URL", "https://generativelanguage.googleapis.com/v1beta/models")
TIMEOUT_SECONDS = int(os.getenv("GEMINI_TIMEOUT", "30"))
MAJORS_FILE_PATH = os.getenv("MAJORS_FILE_PATH", os.path.join(BASE_DIR, "majors_data.json"))
LOG_FILE_PATH = os.getenv("PY_BACKEND_LOG_FILE", os.path.join(BASE_DIR, "backend.log"))

logging.basicConfig(
    level=logging.INFO,
    format="%(asctime)s %(levelname)s %(message)s",
    handlers=[
        logging.StreamHandler(),
        logging.FileHandler(LOG_FILE_PATH, encoding="utf-8"),
    ],
)
logger = logging.getLogger("python_backend")


def _log(message: str) -> None:
    logger.info(message)


def _is_authorized() -> bool:
    if not BACKEND_TOKEN:
        return True

    auth_header = request.headers.get("Authorization", "")
    if not auth_header.startswith("Bearer "):
        return False

    token = auth_header.replace("Bearer ", "", 1).strip()
    return token == BACKEND_TOKEN


def _http_error(message: str, status_code: int, detail: str = "") -> Any:
    payload = {
        "success": False,
        "message": message,
    }
    if detail:
        payload["error"] = detail
    return jsonify(payload), status_code


def _extract_text(data: Dict[str, Any]) -> str:
    return (
        data.get("candidates", [{}])[0]
        .get("content", {})
        .get("parts", [{}])[0]
        .get("text", "")
    )


def _load_majors_file() -> Dict[str, Any]:
    try:
        with open(MAJORS_FILE_PATH, "r", encoding="utf-8") as handle:
            data = json.load(handle)
    except FileNotFoundError:
        return {
            "ok": False,
            "message": "Data jurusan belum tersedia.",
            "data": {"majors": []},
        }
    except json.JSONDecodeError as exc:
        return {
            "ok": False,
            "message": "Data jurusan tidak dapat dibaca.",
            "data": {"majors": []},
        }

    majors = data.get("majors") if isinstance(data, dict) else []
    if not isinstance(majors, list):
        majors = []

    return {
        "ok": True,
        "message": "ok",
        "data": {
            "schema_version": data.get("schema_version", "unknown") if isinstance(data, dict) else "unknown",
            "last_updated": data.get("last_updated", "unknown") if isinstance(data, dict) else "unknown",
            "notes": data.get("notes", "") if isinstance(data, dict) else "",
            "majors": majors,
        },
    }


def _build_majors_context_text(majors_data: Dict[str, Any]) -> str:
    majors = majors_data.get("majors", [])
    if not majors:
        return ""

    lines = [
        "DATA JURUSAN RESMI (WAJIB JADI ACUAN UTAMA)",
        f"schema_version: {majors_data.get('schema_version', 'unknown')}",
        f"last_updated: {majors_data.get('last_updated', 'unknown')}",
        "Gunakan data berikut untuk menjawab informasi jurusan secara konsisten.",
        "Jika ada konflik dengan asumsi model, utamakan data ini.",
    ]

    for index, major in enumerate(majors, start=1):
        name = major.get("name", "-")
        description = major.get("description", "-")
        prospects = major.get("career_prospects", "-")
        preferences = ", ".join(major.get("study_preferences", [])) or "-"
        keywords = ", ".join(major.get("keywords", [])) or "-"

        lines.append(
            f"{index}. {name} | deskripsi: {description} | preferensi studi: {preferences} | prospek: {prospects} | kata kunci: {keywords}"
        )

    return "\n".join(lines)


def _inject_majors_context(payload: Dict[str, Any], majors_context: str) -> Dict[str, Any]:
    if not majors_context:
        return payload

    contents = payload.get("contents", [])
    if not isinstance(contents, list) or not contents:
        return payload

    first = contents[0]
    if isinstance(first, dict) and first.get("role") == "user":
        parts = first.get("parts", [])
        if isinstance(parts, list) and parts and isinstance(parts[0], dict):
            original_text = str(parts[0].get("text", ""))
            parts[0]["text"] = f"{majors_context}\n\n{original_text}"
            return payload

    contents.insert(0, {"role": "user", "parts": [{"text": majors_context}]})
    return payload


@app.get("/health")
def health() -> Any:
    majors_result = _load_majors_file()
    majors_count = len(majors_result["data"].get("majors", []))
    _log("[PY-BACKEND] GET /health")
    return jsonify(
        {
            "ok": True,
            "gemini_key_configured": bool(GEMINI_API_KEY),
            "majors_file": MAJORS_FILE_PATH,
            "majors_loaded": majors_count,
            "majors_valid": majors_result["ok"],
        }
    )


@app.get("/api/majors")
def majors() -> Any:
    result = _load_majors_file()
    status = 200 if result["ok"] else 500
    return jsonify(result), status


@app.post("/api/chat")
def chat() -> Any:
    _log("[PY-BACKEND] POST /api/chat")
    if not _is_authorized():
        _log("[PY-BACKEND] Unauthorized request")
        return _http_error(
            "Akses ke layanan chatbot ditolak.",
            401,
        )

    if not GEMINI_API_KEY:
        return _http_error(
            "Layanan chatbot belum siap digunakan.",
            500,
        )

    body = request.get_json(silent=True) or {}
    payload = body.get("payload")
    models = body.get("models", ["gemini-2.5-flash", "gemini-2.0-flash", "gemini-2.0-flash-lite"])

    if not isinstance(payload, dict) or not payload.get("contents"):
        _log("[PY-BACKEND] Invalid payload")
        return _http_error(
            "Pesan belum dapat diproses. Silakan perjelas pertanyaan Anda.",
            422,
        )

    majors_result = _load_majors_file()
    if majors_result["ok"]:
        majors_context = _build_majors_context_text(majors_result["data"])
        payload = _inject_majors_context(payload, majors_context)
        _log(f"[PY-BACKEND] Majors context loaded: {len(majors_result['data'].get('majors', []))} jurusan")
    else:
        _log(f"[PY-BACKEND] Warning: {majors_result['message']}")

    last_error = ""
    for model in models:
        url = f"{GEMINI_BASE_URL}/{model}:generateContent?key={GEMINI_API_KEY}"
        try:
            resp = requests.post(
                url,
                json=payload,
                headers={"Content-Type": "application/json"},
                timeout=TIMEOUT_SECONDS,
            )
        except requests.RequestException as exc:
            last_error = str(exc)
            continue

        if resp.ok:
            data = resp.json()
            text = _extract_text(data)
            if text:
                _log(f"[PY-BACKEND] Success using model: {model}")
                return jsonify({"success": True, "message": text, "model": model})
            last_error = "Jawaban dari layanan tidak ditemukan."
            continue

        if resp.status_code in (404, 429):
            if resp.status_code == 429:
                time.sleep(1)
            if resp.status_code == 429:
                last_error = (
                    "Layanan chatbot sedang ramai digunakan. Silakan tunggu sebentar lalu coba lagi."
                )
            else:
                last_error = "Layanan chatbot sementara tidak tersedia."
            continue

        last_error = (
            "Terjadi gangguan pada layanan chatbot."
        )

    _log(f"[PY-BACKEND] Failed all models: {last_error}")
    return _http_error(
        "Maaf, layanan chatbot sedang mengalami gangguan. Silakan coba kembali beberapa saat lagi.",
        502,
        last_error,
    )


if __name__ == "__main__":
    host = os.getenv("PY_BACKEND_HOST", "0.0.0.0")
    port = int(os.getenv("PY_BACKEND_PORT", "5000"))
    debug = os.getenv("PY_BACKEND_DEBUG", "true").lower() == "true"
    app.run(host=host, port=port, debug=debug)
