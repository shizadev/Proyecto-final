#De momento esto es para crear reportes nada mas
#Si se te ocurre algo mas que podamos agregarle me avisas

import mysql.connector

def reportar():
    conn = mysql.connector.connect(
        host="db",
        user="usuario",
        password="password_seguro_123",
        database="ProyectoFin"
    )
    cursor = conn.cursor()
    cursor.execute("SELECT COUNT(*) FROM tickets;")
    ticket = cursor.fetchone()[0]

    with open("/shared/reporte_tickets.txt", "w") as f:
        f.write(f"Tickets creados: {ticket}\n")

    cursor.close()
    conn.close()

if __name__ == "__main__":
    reportar()
