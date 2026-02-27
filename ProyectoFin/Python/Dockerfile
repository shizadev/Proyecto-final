# Esta parte no me quedo clara, busque y me decia que debia 
# crear un dockerfile para la carpeta de python
# Creo que eso es todo con este archivo

FROM python:3.11-slim

WORKDIR /app

COPY reporte.py .

RUN pip install mysql-connector-python

CMD ["python", "reporte.py"]