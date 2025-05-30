# Daftar aturan (rules) untuk sistem pakar
# Setiap rule memiliki bagian "if" (kondisi gejala) dan "then" (hasil diagnosis)
rules = [
    {"if": {"nyeri saat bak", "nyeri perut", "keluar cairan dari alat kelamin"}, "then": "Klamidia"},
    {"if": {"perih saat bak", "nyeri pada testis", "keluar cairan dari penis"}, "then": "Gonore"},
    {"if": {"luka lepuh di kisaran alat vital", "nyeri di kisaran alat vital", "kulit kisaran rasa terbakar"}, "then": "Harpes Genital"},
    {"if": {"ruam kulit", "demam", "luka pada alat kelamin"}, "then": "Klamidia"}
]

# Fungsi forward chaining untuk melakukan inferensi berdasarkan gejala (facts)
def forward_chaining(facts):
    # Konversi gejala input menjadi set agar mudah dibandingkan
    known_facts = set(facts)

    # Set untuk menyimpan hasil diagnosis yang disimpulkan
    inferred = set()

    # Variabel untuk melacak apakah ada perubahan (pengetahuan baru ditemukan)
    changed = True

    # Ulangi proses inferensi selama masih ada perubahan (pengetahuan baru)
    while changed:
        changed = False  # Reset status perubahan
        for rule in rules:
            # Jika semua gejala pada rule "if" sudah ada di known_facts dan hasilnya belum pernah disimpulkan
            if rule["if"].issubset(known_facts) and rule["then"] not in known_facts:
                # Tambahkan hasil diagnosis ke known_facts dan inferred
                known_facts.add(rule["then"])
                inferred.add(rule["then"])
                changed = True  # Tandai bahwa ada perubahan (pengetahuan baru ditemukan)

    # Kembalikan semua hasil diagnosis dalam bentuk list
    return list(inferred)
