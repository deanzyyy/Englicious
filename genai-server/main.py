# Import modul FastAPI untuk membuat REST API
from fastapi import FastAPI

# Import BaseModel dari Pydantic untuk validasi data input
from pydantic import BaseModel

# Import typing untuk mendefinisikan tipe List dan Dict
from typing import List, Dict

# Modul dotenv untuk mengambil environment variable dari file .env
from dotenv import load_dotenv

# Import Google Generative AI (Gemini)
import google.generativeai as genai

# Modul os untuk akses ke variabel lingkungan
import os

# Memuat variabel lingkungan dari file .env
load_dotenv()

# Konfigurasi API key untuk layanan Gemini
genai.configure(api_key=os.getenv("GOOGLE_API_KEY"))

# Membuat instance model Gemini (versi flash)
model = genai.GenerativeModel("gemini-2.0-flash")

# Membuat instance aplikasi FastAPI
app = FastAPI()

# -----------------------------------------------
# Definisi Model Data Input untuk Chatbot
# -----------------------------------------------

# Struktur data yang dikirim ke endpoint /ask
class Prompt(BaseModel):
    prompt: str  # Teks pertanyaan dari user
    history: List[Dict[str, str]] = []  # Riwayat percakapan sebelumnya

# -----------------------------------------------
# Endpoint untuk Chatbot Gemini
# -----------------------------------------------

@app.post("/ask")
async def ask_genai(data: Prompt):
    try:
        # Format ulang riwayat percakapan agar sesuai dengan format Gemini API
        history = [
            {
                "role": "user" if msg["role"] == "user" else "model",
                "parts": [msg["text"]]
            }
            for msg in data.history
        ]

        # Memulai sesi chat baru dengan riwayat
        chat = model.start_chat(history=history)

        # Mengirim prompt terbaru ke model dan mendapatkan respons
        response = chat.send_message(data.prompt)

        # Mengembalikan jawaban dari model
        return {"response": response.text}
    except Exception as e:
        # Jika terjadi error, kirimkan pesan error
        return {"error": str(e)}
