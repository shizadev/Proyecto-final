# Listo

FROM python:3.11-slim

WORKDIR /app

COPY reporte.py .

RUN pip install mysql-connector-python

CMD ["python", "reporte.py"]