# -*- coding: utf-8 -*-
"""
Created on Wed Apr 19 00:42:48 2023

@author: joshi
"""
import os
import time
import pymysql
from telethon.sync import TelegramClient
from telethon.tl.types import InputMediaUploadedPhoto, InputMediaUploadedDocument
from telethon.errors.rpcerrorlist import PeerFloodError

# Database connection
def get_data_from_database(user_id):
    connection = pymysql.connect(host='localhost',
                                 user='root',
                                 password='',
                                 db='my_database')
    try:
        with connection.cursor() as cursor:
            query = f"SELECT api_id, api_hash, telefoon_nummer, message, pauze_seconden, uit_aan FROM telegram_bot WHERE user_id = {user_id}"
            cursor.execute(query)
            data = cursor.fetchone()
            return data
    finally:
        connection.close()

def find_file_with_ext(prefix, exts):
    for ext in exts:
        file_path = f"{prefix}{ext}"
        if os.path.exists(file_path):
            return file_path
    return None

user_id = 2
data = get_data_from_database(user_id)
    
api_id = '1696761'
api_hash = '03c46c26c7c87ac8de9eccc8c4f164'
phone_number = '+31687636045'
message = data[3]
pauze_seconden = data[4]
uit_aan = data[5]
    
# Upload a file and return its InputMedia type
def upload_file(client, file_path):
    file_ext = os.path.splitext(file_path)[1].lower()
    if file_ext in ['.jpg', '.jpeg', '.png', '.gif']:
        media = client.upload_file(file_path)
        return InputMediaUploadedPhoto(file=media)
    elif file_ext in ['.mp4', '.mkv', '.webm', '.avi']:
        media = client.upload_file(file_path)
        return InputMediaUploadedDocument(file=media, mime_type='video/mp4', attributes=[])
    return None

client = TelegramClient('sender_session', api_id, api_hash)
client.connect()

if not client.is_user_authorized():
    client.send_code_request(phone_number)
    client.sign_in(phone_number, input('Enter the code: '))

with open('groups.txt', 'r') as file:
    group_ids = file.read().splitlines()

image_exts = ['.jpg', '.jpeg', '.png', '.gif']
video_exts = ['.mp4', '.mkv', '.webm', '.avi']
all_exts = image_exts + video_exts

while True:
    data = get_data_from_database(user_id)
    message = data[3]
    pauze_seconden = data[4]
    uit_aan = data[5]

    if uit_aan == 1:
        for group_id in group_ids:
            try:
                media = []
                for i in range(1, 3):  # Loop through files with names 1 and 2
                    file_path = find_file_with_ext(f"{i}", all_exts)
                    if file_path:
                        media.append(upload_file(client, file_path))

                if media:
                    client.send_message(int(group_id), message, file=media)
                else:
                    client.send_message(int(group_id), message)
                print(f'Message sent to group {group_id}')
            except PeerFloodError:
                print(f'Error sending message to group {group_id}: too many requests')
                time.sleep(60)
            except Exception as e:
                print(f'Error sending message to group {group_id}: {e}')

        time.sleep(pauze_seconden)
    else:
        time.sleep(20)
