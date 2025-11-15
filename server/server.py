import socket
import mysql.connector
import re

HOST = "127.0.0.1"
PORT = 9999

db = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="smartparking"
)

cursor = db.cursor()

server = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
server.bind((HOST, PORT))
server.listen(5)

print("Server parkir berjalan...")

while True:
    client, addr = server.accept()
    print("Terhubung dengan scanner:", addr)

    data = client.recv(1024).decode()
    print("Data diterima:", data)

    try:
        nim, nama = data.split("-", 1)
        nim = nim.strip()

        if not nim.lower().startswith("d104"):
            client.send(
                "Bukan mahasiswa informatika! Lapor maksud kedatangan ke petugas parkir.".encode())
            client.close()
            continue

        cursor.execute("""
            SELECT id FROM log_parkir
            WHERE nim=%s AND waktu >= NOW() - INTERVAL 5 SECOND
        """, (nim,))
        if cursor.fetchone():
            client.send("Sudah tercatat, silakan masuk.".encode())
            client.close()
            continue

        cursor.execute(
            "INSERT INTO log_parkir (nim, nama, waktu) VALUES (%s, %s, NOW())",
            (nim, nama)
        )
        db.commit()

        client.send("OK - Disimpan".encode())

    except Exception as e:
        print("Error:", e)
        client.send(("ERROR: " + str(e)).encode())

    client.close()
