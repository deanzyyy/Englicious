#!/bin/bash
genaienv/Scripts/activate
uvicorn main:app --host 127.0.0.1 --port 8008 --reload
