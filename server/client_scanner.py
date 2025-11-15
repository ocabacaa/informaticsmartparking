import cv2
from pyzbar import pyzbar
import socket

HOST = "127.0.0.1"
PORT = 9999

cap = cv2.VideoCapture(0)
print("Arahkan kamera ke QR code mahasiswa...")

while True:
    ret, frame = cap.read()
    decoded = pyzbar.decode(frame)

    for obj in decoded:
        qr_data = obj.data.decode("utf-8")
        print("QR Code terbaca:", qr_data)

        client = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        client.connect((HOST, PORT))
        client.send(qr_data.encode())

        response = client.recv(1024).decode()
        print("Respon server:", response)

        client.close()
        cv2.rectangle(frame, (obj.rect.left, obj.rect.top),
                      (obj.rect.left + obj.rect.width,
                       obj.rect.top + obj.rect.height),
                      (0, 255, 0), 2)
        cv2.putText(frame, response, (obj.rect.left, obj.rect.top - 10),
                    cv2.FONT_HERSHEY_SIMPLEX, 1, (0, 255, 0), 2)

    cv2.imshow("Scanner Parkir", frame)
    if cv2.waitKey(1) == ord('q'):
        break

cap.release()
cv2.destroyAllWindows()
