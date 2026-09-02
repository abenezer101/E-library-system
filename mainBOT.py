import telebot
from telebot import types
import mysql.connector
import keyboard
# from flask import Flask , jsonify
import threading
import os
import time
import requests
# Replace with your actual database credentials and bot token
db_config = {
    'user': 'root',
    'host': 'localhost',
    'database': 'elibrary',
}
bot_token = ''

# Define the base directory
BASE_DIR = os.path.dirname(os.path.abspath(__file__))
Announcement_DIR = os.path.join(BASE_DIR, 'Dashboard', 'pages')
PDF_DIR=BASE_DIR


bot = telebot.TeleBot(bot_token)

# app = Flask(__name__)

def get_db_connection():
    return mysql.connector.connect(**db_config)

# @app.route('/health', methods=['GET'])
# def health_check():
#     return jsonify({'status': 'ok'}), 200

# def run_flask():
#     app.run(host='0.0.0.0', port=5000)



def print_ascii_art():
    green_color = "\033[92m"
    # Reset ANSI escape code (to default colors) at the end of the ASCII art
    reset_color = "\033[0m"
    # ASCII art for your program
    art = """


███╗   ███╗███████╗██╗     ██╗   ██╗      ███████╗    ███████╗    ██╗     ██╗██████╗ ██████╗  █████╗ ██████╗ ██╗   ██╗
████╗ ████║██╔════╝██║     ██║   ██║      ██╔════╝    ██╔════╝    ██║     ██║██╔══██╗██╔══██╗██╔══██╗██╔══██╗╚██╗ ██╔╝
██╔████╔██║█████╗  ██║     ██║   ██║█████╗█████╗      █████╗█████╗██║     ██║██████╔╝██████╔╝███████║██████╔╝ ╚████╔╝ 
██║╚██╔╝██║██╔══╝  ██║     ██║   ██║╚════╝██╔══╝      ██╔══╝╚════╝██║     ██║██╔══██╗██╔══██╗██╔══██║██╔══██╗  ╚██╔╝  
██║ ╚═╝ ██║███████╗███████╗╚██████╔╝      ███████╗    ███████╗    ███████╗██║██████╔╝██║  ██║██║  ██║██║  ██║   ██║   
╚═╝     ╚═╝╚══════╝╚══════╝ ╚═════╝       ╚══════╝    ╚══════╝    ╚══════╝╚═╝╚═════╝ ╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝   ╚═╝   
                                                                                                                      

                                                     
    """
    print(green_color+art+reset_color)
    print(green_color+"Melu'e E-library System"+reset_color)
    print(green_color+"Developed by @abenezer101"+reset_color)
    print(green_color+"\nHello Admin,"+reset_color+"\nThe program is running success fully")





def main():



    # Print ASCII art when the program starts
    print_ascii_art()

    # flask_thread = threading.Thread(target=run_flask)
    # flask_thread.start()


    def user_exists(telegram_id):
        conn = get_db_connection()
        cursor = conn.cursor()
        cursor.execute("SELECT id FROM users WHERE telegram_id = %s", (telegram_id,))
        result = cursor.fetchone()
        conn.close()
        return result is not None


    def is_registration_approved(telegram_id):
        conn = get_db_connection()
        cursor = conn.cursor()
        cursor.execute("SELECT registration_status FROM users WHERE telegram_id = %s", (telegram_id,))
        result = cursor.fetchone()
        conn.close()
        return result is not None and result[0]


    def get_user_language(telegram_id):
        conn = get_db_connection()
        cursor = conn.cursor()
        cursor.execute("SELECT preferred_language FROM users WHERE telegram_id = %s", (telegram_id,))
        result = cursor.fetchone()
        conn.close()
        return result[0] if result else 'English'


    # Decorator to ensure the user is registered and approved
    def ensure_registered(func):
        def wrapper(message, *args, **kwargs):
            telegram_id = message.from_user.id
            if user_exists(telegram_id):
                if is_registration_approved(telegram_id):
                    return func(message, *args, **kwargs)
                else:
                    bot.send_message(message.chat.id, "Your registration is pending approval. Please wait for an admin to approve your registration.")
            else:
                bot.send_message(message.chat.id, "Please register or login to continue.")
                show_registration_options(message)
        return wrapper


    # Handlers
    @bot.message_handler(commands=['start'])
    def send_welcome(message):
        telegram_id = message.from_user.id
        first_name = message.from_user.first_name

        conn = get_db_connection()
        cursor = conn.cursor()
        cursor.execute("SELECT telegram_id FROM users WHERE telegram_id = %s", (telegram_id,))
        user_exists = cursor.fetchone()

        if not user_exists:
            handle_contact(message)
        conn.close()

        user_language = get_user_language(telegram_id)
        response = responses[user_language]['welcome'].format(first_name=first_name)
        bot.send_message(message.chat.id, response)
        show_registration_options(message)


    def show_registration_options(message):
        user_language = get_user_language(message.from_user.id)
        response = responses[user_language]['register_prompt']
        keyboard = types.ReplyKeyboardMarkup(one_time_keyboard=True, resize_keyboard=True)
        button_register = types.KeyboardButton(text="Register / Login")
        keyboard.add(button_register)
        bot.send_message(message.chat.id, response, reply_markup=keyboard)


    @bot.callback_query_handler(func=lambda call: call.data.startswith('set_language_'))
    def set_language(call):
        language = 'English' if call.data == 'set_language_english' else 'Amharic'
        telegram_id = call.message.chat.id

        conn = get_db_connection()
        cursor = conn.cursor()
        cursor.execute("UPDATE users SET preferred_language = %s WHERE telegram_id = %s", (language, telegram_id))
        conn.commit()
        conn.close()

        response = responses[language]['language_updated'].format(language=language)
        bot.send_message(call.message.chat.id, response)
        handle_account_settings(call.message)


    @bot.message_handler(func=lambda message: message.text == "Register / Login")
    def register_or_login(message):
        telegram_id = message.from_user.id
        user_language = get_user_language(telegram_id)
        if user_exists(telegram_id):
            if is_registration_approved(telegram_id):
                response = responses[user_language]['Already_registered']
                bot.send_message(message.chat.id, response)
                show_menu(message)
            else:
                response = responses[user_language]['Registration_Pending']
                bot.send_message(message.chat.id, response)
        else:
            request_phone_number(message)


    def request_phone_number(message):
        telegram_id = message.from_user.id
        user_language = get_user_language(telegram_id)
        response = responses[user_language]['share_phonenumber']
        keyboard = types.ReplyKeyboardMarkup(one_time_keyboard=True, resize_keyboard=True)
        button_phone = types.KeyboardButton(text="Share phone number", request_contact=True)
        keyboard.add(button_phone)
        bot.send_message(message.chat.id, response, reply_markup=keyboard)


    @bot.message_handler(content_types=['contact'])
    def handle_contact(message):
        if message.contact:
            telegram_id = message.contact.user_id
            phone_number = message.contact.phone_number
            first_name = message.from_user.first_name
            last_name = message.from_user.last_name or ""
            full_name = f"{first_name} {last_name}".strip()  # Combine first_name and last_name
            
            username = message.from_user.username or ""

            if user_exists(telegram_id):
                if is_registration_approved(telegram_id):
                    bot.send_message(message.chat.id, "You are already registered and approved. Logged in successfully!")
                    show_menu(message)
                else:
                    bot.send_message(message.chat.id, "Your registration is pending approval. Please wait for an admin to approve your registration.")
                    register_or_login(message)
            else:
                conn = get_db_connection()
                cursor = conn.cursor()
                cursor.execute(
                    "INSERT INTO users (telegram_id, username, first_name, phone_number, registration_status) VALUES (%s, %s, %s, %s, %s)",
                    (telegram_id, username, full_name, phone_number, False)
                )
                conn.commit()
                conn.close()
                bot.send_message(message.chat.id, "Thank you! Now please send a valid school ID or kebele ID as a photo.")
                bot.register_next_step_handler(message, handle_valid_id)


    def handle_valid_id(message):
        if message.content_type == 'photo':
            telegram_id = message.from_user.id
            first_name = message.from_user.first_name
            photo_file = bot.get_file(message.photo[-1].file_id)
            photo_path = bot.download_file(photo_file.file_path)

            valid_id_dir = os.path.join('Dashboard', 'pages', 'valid_ids')
            valid_id_dir_for_db = 'valid_ids'

            os.makedirs(valid_id_dir, exist_ok=True)
            photo_save_path = os.path.join(valid_id_dir, f'{first_name}_IDcard.jpg')
            photo_save_path_for_db = os.path.join(valid_id_dir_for_db, f'{first_name}_IDcard.jpg')

            with open(photo_save_path, 'wb') as new_file:
                new_file.write(photo_path)

            conn = get_db_connection()
            cursor = conn.cursor()
            cursor.execute("UPDATE users SET valid_id = %s WHERE telegram_id = %s", (photo_save_path, telegram_id))
            conn.commit()
            conn.close()

            bot.send_message(message.chat.id, "Your ID has been received. Please wait for an admin to approve your registration.")
            show_registration_options(message)
        else:
            bot.send_message(message.chat.id, "Please send a valid ID as a photo.")
            bot.register_next_step_handler(message, handle_valid_id)


    @ensure_registered
    def show_profile(message):
        telegram_id = message.chat.id
        conn = get_db_connection()
        cursor = conn.cursor()
        cursor.execute("SELECT first_name, username, phone_number, profile_picture FROM users WHERE telegram_id = %s", (telegram_id,))
        user = cursor.fetchone()
        conn.close()

        markup = types.InlineKeyboardMarkup()
        edit_button = types.InlineKeyboardButton("Edit Profile", callback_data='edit_profile')
        markup.add(edit_button)

        bot.send_message(message.chat.id, f"Name: {user[0]}\nUsername: {user[1]}\nPhone: {user[2]}\nProfile Picture: {user[3]}", reply_markup=markup)


    @bot.callback_query_handler(func=lambda call: call.data == 'edit_name')
    def edit_name(call):
        bot.send_message(call.message.chat.id, "Please enter your new name:")
        bot.register_next_step_handler(call.message, update_name)


    def update_name(message):
        new_name = message.text
        telegram_id = message.chat.id
        conn = get_db_connection()
        cursor = conn.cursor()
        cursor.execute("UPDATE users SET first_name = %s WHERE telegram_id = %s", (new_name, telegram_id))
        conn.commit()
        conn.close()
        bot.send_message(message.chat.id, "Your name has been updated successfully!")
        show_profile(message)
    
    ##########################################################################################################################################
    #############################################                                 ############################################################
    #############################################    E-books btn handler code     ############################################################
    #############################################                                 ############################################################
    ##########################################################################################################################################




    @bot.message_handler(func=lambda message: message.text == "E-Books")
    @ensure_registered
    def show_menu(message):
        markup = telebot.types.ReplyKeyboardMarkup(row_width=3, resize_keyboard=True)
        
        button_books = telebot.types.KeyboardButton('E-Books')
        button_Hard_copy_books = telebot.types.KeyboardButton('Rent book')
        web_app_button = telebot.types.KeyboardButton(
            text='View Books',
            web_app=telebot.types.WebAppInfo("https://meleufoundationbooklist.netlify.app/")
        )
        button_Announcement = telebot.types.KeyboardButton('Announcement')
        button_account_settings = telebot.types.KeyboardButton('Account settings')
        button_information = telebot.types.KeyboardButton('General Information')
        button_contact_developer = telebot.types.KeyboardButton('Contact Developer')
        
        markup.add(button_books,button_Hard_copy_books, web_app_button)
        markup.add(button_Announcement, button_account_settings)
        markup.add(button_information)
        markup.add(button_contact_developer)
        telegram_id = message.from_user.id
        user_language = get_user_language(telegram_id)
        response = responses[user_language]['book_request']
        
        bot.send_message(message.chat.id, response, reply_markup=markup)


    ####################################################################################################################################################################
    #############################################                                                           ############################################################
    #############################################    Account Settings Btn & User Profile edit handler code  ############################################################
    #############################################                                                           ############################################################
    ####################################################################################################################################################################

    @bot.message_handler(func=lambda message: message.text == "Account settings")
    @ensure_registered
    def handle_account_settings(message):
        markup = types.InlineKeyboardMarkup()
        edit_name_button = types.InlineKeyboardButton("Edit Name", callback_data='edit_name')
        language_settings_button = types.InlineKeyboardButton("Language Settings", callback_data='language_settings')
        markup.add(edit_name_button, language_settings_button)
        telegram_id = message.from_user.id
        user_language = get_user_language(telegram_id)
        response = responses[user_language]['edit_profile']
        bot.send_message(message.chat.id, response, reply_markup=markup)

    @bot.callback_query_handler(func=lambda call: call.data == 'language_settings')
    def language_settings(call):
        markup = types.InlineKeyboardMarkup()
        english_button = types.InlineKeyboardButton("English", callback_data='set_language_english')
        amharic_button = types.InlineKeyboardButton("Amharic", callback_data='set_language_amharic')
        markup.add(english_button, amharic_button)
        bot.send_message(call.message.chat.id, "Please choose your preferred language:", reply_markup=markup)

    @bot.callback_query_handler(func=lambda call: call.data.startswith('set_language_'))
    def set_language(call):
        language = 'English' if call.data == 'set_language_english' else 'Amharic'
        telegram_id = call.message.chat.id
        
        conn = get_db_connection()
        cursor = conn.cursor()
        cursor.execute("UPDATE users SET preferred_language = %s WHERE telegram_id = %s", (language, telegram_id))
        conn.commit()
        conn.close()
        
        bot.send_message(call.message.chat.id, f"Language setting has been updated to {language}.")
        handle_account_settings(call.message)
    
    def get_user_language(telegram_id):
        conn = get_db_connection()
        cursor = conn.cursor()
        cursor.execute("SELECT preferred_language FROM users WHERE telegram_id = %s", (telegram_id,))
        result = cursor.fetchone()
        conn.close()
        return result[0] if result else 'English'



    @bot.callback_query_handler(func=lambda call: call.data == 'edit_name')
    def edit_name(call):
        bot.send_message(call.message.chat.id, "Please enter your new name:")
        bot.register_next_step_handler(call.message, update_name)

    def update_name(message):
        new_name = message.text
        telegram_id = message.chat.id
        conn = get_db_connection()
        cursor = conn.cursor()
        cursor.execute("UPDATE users SET first_name = %s WHERE telegram_id = %s", (new_name, telegram_id))
        conn.commit()
        conn.close()
        bot.send_message(message.chat.id, "Your name has been updated successfully!")
        show_profile(message)



    ###################################### language dictionary Start #################################################
    responses = {
        'English': {
            'welcome': "Welcome {first_name} to the E-Library!",
            'register_prompt': "Please register or login to continue:",
            'share_phonenumber':"Please share your phone number ",
            'register_success': "Thank you! Now please send a valid school ID or kebele ID as a photo.",
            'Already_registered':"You are already registered and approved. Logged in successfully!",
            'Registration_Pending':"Your registration is pending approval. Please wait for an admin to approve your registration.",
            'id_received': "Your ID has been received. Please wait for an admin to approve your registration.",
            'edit_profile':"What would you like to change?",
            'update_name_success': "Your name has been updated successfully!",
            'language_updated': "Language setting has been updated to {language}.",
            'information':"##### 𝕄𝕖𝕝𝕦'𝕖 𝔽𝕠𝕦𝕟𝕕𝕒𝕥𝕚𝕠𝕟 𝔼-𝕝𝕚𝕓𝕣𝕒𝕣𝕪 #####\n\n\nMelu'e Foundation is dedicated to supporting children living with autism and other special needs, as well as their parents and guardians. With a strong mission to integrate people with special needs into society, the foundation has created an inclusive E-library system. This library is designed to cater to children with special needs and anyone interested in learning more about their lives.\n\n\n<b>Melu'e Foundation Library Opening Hours:</b>\n\nThe Melu'e Foundation E-library is open to all and operates on the following days:\n\n<blockquote>Monday : 8:30 AM to 5:00 PM \n\nTuesday : 8:30 AM to 5:00 PM \n\nWednesday : 8:30 AM to 5:00 PM \n\nThursday : 8:30 AM to 5:00 PM \n\nFriday : 8:30 AM to 5:00 PM\n</blockquote> \nThe library offers a wide range of books, including resources specifically about and for children with special needs.\n\n<b>How to Become a Member of the Melu'e Foundation E-Library</b>\n\n\nTo access the library and rent books, users need to register through the following steps:\n\n<b>1.Start the Bot: Begin the registration process by interacting with the bot.</b>\n\n<b>2.Provide Contact Information: Share your phone number for registration purposes.</b>\n\n<b>3.Submit Valid ID: Send a valid official ID card recognized by the government or an agency. Accepted IDs include Kebele ID, Fayda National ID, School ID, and passport.</b>\n\n<b>4.Contact Melu'e Foundation Admin: Inform the foundation administrator about your registration application. The admin will instruct you to plant a tree on the foundation's compound. Ensure you contact the library admin first to authorize your planting.</b>\n\n<b>5.Plant a Tree: After planting the tree and taking a photo with it, submit the photo to the admin for verification. Once the admin confirms the completion of this step, your registration will be approved, and you can start borrowing books.</b>\n\n\nFor those interested in exploring the available books, please visit the following link:\nhttps://t.me/MelueFoundationE_libraryBot/BookLists \n\n\nThe Melu'e Foundation E-Library welcomes everyone to join and engage with a world of books, fostering understanding and inclusion for children with special needs.\n\n\nJoin our community through our social medias below \n",
            'contact_message': "This Bot was made in collab with: Abenezer Abera, Michael Habtamu, Nohami Berhanu, and Melu'e Foundation IT Team. Click the button below to contact the developers behind this program.",
            'book_request':"Please enter the VOUCHER CODE of the book you want to download:",
            'book_not_found': "Book not found.",
            'no_new_Announcement': "No new Announcement."
        },
        'Amharic': {
            'welcome': "ሰላም {first_name} እንኳን ወደ MELU'E FOUDATION E-Library! በሰላም መጡ። ",
            'register_prompt': "እባክዎን ለመቀጠል 'Register/Login'ንን ይጫኑ።",
            'share_phonenumber':"እባክዎ በስልክ ቁጥሮ ይመዝገቡ",
            'register_success': "እናመሰግናለን! በቀጣይ የትምህርት ቤት መታወቂያዎን ወይም የቀበሌ መታወቂያዎን ያስገቡ።",
            'Already_registered':"አስቀድመው ተመዝገበዋል፣ በተሳካ ሁኔታም ገብተዋል!",
            'Registration_Pending':"ምዝግባዎ እየታየ ነው፣ አድሚኑ ጥያቄውን እስኪቀበል ይታገሱ።",
            'id_received': "መለያ IDዎ/መታወቂያዎ ደርሶናል። እባኮ አድሚኑ ጥያቄዎን እስኪቀበል ድረስ ትንሽ ጠብቀው 'Register/Login'ንን በድጋሚ ይጫኑ።",
            'edit_profile':"ከታች ባሉት ምን መቀየር ይፈሊጋሉ?",
            'update_name_success': "ስምዎ በስትክክል ተቀይሯል ።",
            'language_updated': "ቋንቋ ወደ {language} ተቀይሯል።",
            'information':"##### ምሉዕ ፋውንዴሽን ኢ-ላይብረሪ #####\n\n\nምሉዕ ፋውንዴሽን በኦቲዝም ጥላ ስር ለሚኖሩ ህጻናትን እንዲሁም ወላጆቻቸውን እና አሳዳጊዎቻቸውን ለመደገፍ የሚሰራ የበጎ አድራጎት ተቋም ነው።በኦቲዝም ጥላ ስር ለሚኖሩ ልጆች  ከህብረተሰቡ ጋር ለማዋሃድ እና አቻ ለማድረግ ካለው ጠንካራ ተልዕኮ፣ ፋውንዴሽኑ ሁሉን ያካተተ ኢ-ላይብረሪ ገጽ ፈጥሯል። ይህ ቤተ መፃህፍት የተነደፈው በኢትዮጵያ ውስጥ ከኦቲዝም ጥላ ስር የሚኖሩ ልጆችን ከህብረተሰቡ ጋር አንድ ላይ አዋህዶ ፤የመጽሃፍት አለምን አንድ ላይ ለማሰስ እና ስለኦቲዝም ጥላ ውስጥ ላሉ እናም ለህብረተሰቡ አንድ መገናኛ ማህበርን የፈጥራል ተብሎ ታስቦ የተሰራ ነው።\n\n\n<b>የምሉዕ ፋውንዴሽን ቤተ መፃህፍት የመክፈቻ ሰዓታት:</b>\n\nየምሉዕ ፋውንዴሽን ቤተ መጽሃፍ ለሁሉም ክፍት ነው፤ በሚከተሉትም ቀናት ይሰራል:\n\n<blockquote>ሰኞ : 2:30 AM to 11:00 PM \n\nማክሰኞ : 2:30 AM to 11:00 PM \n\nረቡዕ : 2:30 AM to 11:00 PM \n\nህሙስ : 2:30 AM to 11:00 PM \n\nዓርብ : 2:30 AM to 11:00 PM\n</blockquote> \nቤተ መፃህፍቱ በኦቲዝም ጥላ ስር ላሉ ህጻናት መገልገያዎችን ጨምሮ ብዙ አይነት መጽሃፎችን ያቀርባል።\n\n<b>የምሉዕ ፋውንዴሽን ኢ-ላይብረሪ እንዴት አባል መሆን ይቻላል?</b>\n\n\nየሚፈልጉትን መጽሐፍ ለማግኘት ወይም ለመከራየት ተጠቃሚ የቤተ መጽሃፉ አባል መሆን አለበት፤ ሚከተሉት መንገዶች በመከተል አባል ምሆን ይችላል ።\n\n<b>1.Start the Bot: Begin the registration process by interacting with the bot.</b>\n\n<b>2.Provide Contact Information: Share your phone number for registration purposes.</b>\n\n<b>3.Submit Valid ID: Send a valid official ID card recognized by the government or an agency. Accepted IDs include Kebele ID, Fayda National ID, School ID, and passport.</b>\n\n<b>4.Contact Melu'e Foundation Admin: Inform the foundation administrator about your registration application. The admin will instruct you to plant a tree on the foundation's compound. Ensure you contact the library admin first to authorize your planting.</b>\n\n<b>5.Plant a Tree: After planting the tree and taking a photo with it, submit the photo to the admin for verification. Once the admin confirms the completion of this step, your registration will be approved, and you can start borrowing books.</b>\n\n\nቤተ መጽሃፉ ያሉትን መጽሐፎች ማየት ለሚፈልጉ፣ እባክዎ የሚከተለውን ሊንክ/ማፈንጥሪያ የንኩ፡\nhttps://t.me/MelueFoundationE_libraryBot/BookLists \n\n\nበምሉዕ ፋውንዴሽን ቤተ-መጽሐፍ ውስጥ ሁሉም ሰው እንዲቀላቀል እና ከመፅሃፍ አለም ጋር እንዲሳተፍ ያበረታታል፣ በተጨማሪም በኦቲዝም ጥላ ስር ስላሉ ልጆች ግንዛቤን እና እውቀት ያጎናጽፋል።\n\n\nከታች ባሉ ማስፈንጥሪያ በተኖች ማህበራዊ ሚዲያዎቻችን በመከታተል ማህበረሰባችንን ይቀላቀሉ።\n",
            'contact_message': "ይህ ቦት በአቤኔዜር አበራ፣ ሚካኤል ሐብታሙ፣ ኖሃሚ በርሃኑ፣ እና የመሉዕ ፋውንዴሽን ቡድን ጋር በትብብር የተሰራ ነው። የዚህን BOT Developer ለማግኘት ከታች ባለው ማስፈንጠሪያ ይንኩ።",
            'book_request':"እባክዎ ማውረድ የሚፈልጉትን መጽሃፍ ቫውቸር ኮዱን ያስገቡ :",
            'book_not_found': "መጽሐፉ አልተገኘም።",
            'no_new_Announcement': "አዲስ ማስታወቂያ አልተገኘም።"
        }
    }
    ###################################### language dictionary End #################################################
    
    
    ###########################################################################################################################################################
    #######################################################                                  ##################################################################
    #######################################################  General Information btn code    ##################################################################
    #######################################################                                  ##################################################################
    ###########################################################################################################################################################

    @bot.message_handler(func=lambda message: message.text == "General Information")
    @ensure_registered
    def contact(message):
        user_language = get_user_language(message.from_user.id)
        response = responses[user_language]['information']
        markup = types.InlineKeyboardMarkup(row_width=1)
        Website_button = types.InlineKeyboardButton("Our website", url='https://melu-e.org', switch_inline_query='Our website')
        Telegram_button = types.InlineKeyboardButton("Telegram", url='https://t.me/Meluefoundation', switch_inline_query='Telegram')
        FaceBook_button = types.InlineKeyboardButton("Facebook", url='https://www.facebook.com/profile.php?id=100089247143385&mibextid=ZbWKwL', switch_inline_query='FaceBook')
        Tictok_button = types.InlineKeyboardButton("Tiktok", url='https://www.tiktok.com/@melu.e_foundation', switch_inline_query='Tictok')
        Instagram_button = types.InlineKeyboardButton("Instagram", url='https://www.instagram.com/melu.e_foundation/', switch_inline_query='Instagram')
        markup.add(Website_button,Telegram_button,FaceBook_button,Tictok_button,Instagram_button)
        bot.send_message(message.chat.id, response, reply_markup=markup, parse_mode='HTML')

    ###########################################################################################################################################################
    ########################################################                                 ##################################################################
    ########################################################    Contact Developer Btn code   ##################################################################
    ########################################################                                 ##################################################################
    ###########################################################################################################################################################

    @bot.message_handler(func=lambda message: message.text == "Contact Developer")
    @ensure_registered
    def contact(message):
        user_language = get_user_language(message.from_user.id)
        response = responses[user_language]['contact_message']
        markup = types.InlineKeyboardMarkup(row_width=1)
        contact_button = types.InlineKeyboardButton("Contact Developer", url='https://t.me/Enat_TechnologiesBot', switch_inline_query='contact')
        markup.add(contact_button)
        bot.send_message(message.chat.id, response, reply_markup=markup)




    ###########################################################################################################################################################
    ########################################################                                 ##################################################################
    ########################################################     Announcement handler code   ##################################################################
    ########################################################                                 ##################################################################
    ###########################################################################################################################################################
    
    @bot.message_handler(func=lambda message: message.text == "Announcement")
    @ensure_registered
    def handle_Announcement(message):
        conn = get_db_connection()
        cursor = conn.cursor()
        cursor.execute("SELECT * FROM announcement")
        Announcements = cursor.fetchall()
        conn.close()

        if Announcements:
            for Announcement in Announcements:
                photo_path = Announcement[4]
                full_photo_path = os.path.join(Announcement_DIR, photo_path)

                caption = f" #Announcement \n<b>Date:</b> <code>{Announcement[5]}</code>\n<b>Author:</b> <code>{Announcement[1]}</code>\n<b>Title:</b> <code>{Announcement[2]}</code>\n<b>Content:</b>\n<blockquote>{Announcement[3]} </blockquote>"
                

                with open(full_photo_path, 'rb') as photo:
                    bot.send_photo(message.chat.id, photo, caption=caption, parse_mode='html')

        else:
            bot.send_message(message.chat.id, "No new Announcement.")
    
    
    
    # constantly Announcement checker

    def get_all_user_ids():
        conn = get_db_connection()
        cursor = conn.cursor()
        cursor.execute("SELECT telegram_id FROM users")
        user_ids = [row[0] for row in cursor.fetchall()]
        conn.close()
        return user_ids

    def fetch_latest_notification():
        conn = get_db_connection()
        cursor = conn.cursor()
        cursor.execute("SELECT * FROM announcement ORDER BY id DESC LIMIT 1")
        notification = cursor.fetchone()
        conn.close()
        return notification

    global last_notification_id
    last_notification_id = None

    def check_for_new_Announcement():
        global last_notification_id
        while True:
            notification = fetch_latest_notification()
            
            if notification and (last_notification_id is None or notification[0] > last_notification_id):
                last_notification_id = notification[0]
                user_ids = get_all_user_ids()
                photo_path = notification[4]
                full_photo_path = os.path.join(Announcement_DIR, photo_path)

                caption = f"#Announcement \n<b>Date:</b> <code>{notification[5]}</code>\n<b>Author:</b> <code>{notification[1]}</code>\n<b>Title:</b> <code>{notification[2]}</code>\n<b>Content:</b>\n<blockquote>{notification[3]} </blockquote>"
                
                for user_id in user_ids:
                    with open(full_photo_path, 'rb') as photo:
                        bot.send_photo(user_id, photo, caption=caption, parse_mode='html')
                        print("New notification sent to all users.")
            
            time.sleep(10)


    
    ###########################################################################################################################################################
    ########################################################                                 ##################################################################
    ########################################################     Book Rent handler code      ##################################################################
    ########################################################                                 ##################################################################
    ###########################################################################################################################################################
    
    @bot.message_handler(func=lambda message: message.text == "Rent book")
    @ensure_registered
    def rent_book(message):
        telegram_id = message.chat.id
        conn = get_db_connection()
        cursor = conn.cursor()

        # Fetch user_id based on telegram_id
        cursor.execute("SELECT * FROM users WHERE telegram_id = %s", (telegram_id,))
        user = cursor.fetchone()
        print(user)

        if not user:
            bot.send_message(message.chat.id, "You are not registered. Please register / login to continue")
            conn.close()
            return

        user_id = user[1]  # Ensure this is the primary key
        first_name = user[3]  # Assuming issuer_name is in the first_name field
        
        # Check if the user is already on the waitlist or has rented a book
        cursor.execute("SELECT wait_list, rented_book FROM issued_books WHERE user_id = %s", (user_id,))
        status_entry = cursor.fetchone()

        if status_entry:
            wait_list_status, rented_book_status = status_entry
            if rented_book_status == 1:
                bot.send_message(message.chat.id, "You already have a rented book. Please return it to rent a new one.")
            elif wait_list_status == 1:
                bot.send_message(message.chat.id, "You are already on the waitlist for renting books.")
            else:
                # Update the wait_list to 1
                cursor.execute("UPDATE issued_books SET wait_list = 1 WHERE user_id = %s", (user_id,))
                conn.commit()
                bot.send_message(message.chat.id, "You have joined the waitlist for renting books. Contact the librarian.")
        else:
            # Insert into issued_books with wait_list set to 1
            try:
                cursor.execute("INSERT INTO issued_books (user_id, issuer_name, wait_list) VALUES (%s, %s, %s)", (user_id, first_name, 1))
                conn.commit()
                bot.send_message(message.chat.id, "You have joined the waitlist for renting books. Contact the librarian.")
            except mysql.connector.IntegrityError as e:
                bot.send_message(message.chat.id, f"An error occurred: {e}")

        conn.close()

    # Fetch all user ids FOR BOOK RENT
    def get_all_user_ids_for_bookrent():
        conn = get_db_connection()
        cursor = conn.cursor()
        cursor.execute("SELECT user_id from messages WHERE message_type='rented_book_msg'")
        user_ids = [row[0] for row in cursor.fetchall()]
        conn.close()
        return user_ids

    # Fetch the latest message FOR BOOK RENT
    def fetch_latest_bookrent_message():
        conn = get_db_connection()
        cursor = conn.cursor()
        cursor.execute("SELECT * FROM messages WHERE message_type='rented_book_msg'")
        message = cursor.fetchone()
        
        print("MESSAGE",message)
        conn.close()
        return message

    2147483647

    # Check for new messages FOR rented books
    def check_for_new_bookrent_messages():
        while True:
            message = fetch_latest_bookrent_message()
            print("checking for message...")
            if message:
                user_ids = get_all_user_ids_for_bookrent()
                
                issuer_name = message[2]
                text = message[3]
                rent_msg='rented_book_msg'

                caption = f"#Notification\n<b>Dear,</b> <code>{issuer_name}</code> You have successfully rented a Book.\n<b>Book Details:</b>\n<blockquote>{text}</blockquote>"
                for user_id in user_ids:
                    print("THE USER ID'S",user_id)
                    bot.send_message(user_id, caption, parse_mode='html')
                    print(f"userID: {user_id}, Name: {issuer_name} have successfully rented book.")
                
                # Delete the message from the database after sending it
                conn = get_db_connection()
                cursor = conn.cursor()
                cursor.execute("DELETE FROM messages WHERE user_id = %s AND message_type=%s", (user_id,rent_msg))
                conn.commit()
                conn.close()
            
            time.sleep(5)
            
            
            
            
            
            
            
            
            
    #################################################

    def get_all_user_ids_for_message():
        conn = get_db_connection()
        cursor = conn.cursor()
        cursor.execute("SELECT user_id from messages WHERE message_type='user_msg'")
        user_ids = [row[0] for row in cursor.fetchall()]
        conn.close()
        return user_ids

    # Fetch the latest message FOR BOOK RENT
    def fetch_latest_message():
        conn = get_db_connection()
        cursor = conn.cursor()
        cursor.execute("SELECT * FROM messages WHERE message_type='user_msg'")
        message = cursor.fetchone()
        conn.close()
        return message

    

    # Check for new messages FOR rented books
    def check_for_new_messages():
        while True:
            message = fetch_latest_message()
            print("checking for user_msg message...")
            if message:
                user_ids = get_all_user_ids_for_message()
                issuer_name = message[2]
                text = message[3]
                user_msg='user_msg'
                caption = f"#Notification\n<b>Dear,</b> <code>{issuer_name}</code> You have recieved notification.\n<b>Details:</b>\n<blockquote>{text}</blockquote>"
                for user_id in user_ids:
                    bot.send_message(user_id, caption, parse_mode='html')
                    print(f"userID: {user_id}, Name: {issuer_name} have successfully got notification.")
                
                # Delete the message from the database after sending it
                conn = get_db_connection()
                cursor = conn.cursor()
                cursor.execute("DELETE FROM messages WHERE user_id = %s AND message_type=%s", (user_id,user_msg))
                conn.commit()
                conn.close()
            
            time.sleep(5)
            
            
            
            
    
    
    
    

    # Start the background thread for checking new announcements
    def start_checking_new_announcements():
        announcement_thread = threading.Thread(target=check_for_new_Announcement, daemon=True)
        announcement_thread.start()

    # Start the background thread for checking new book rent messages
    def start_checking_new_bookrent_messages():
        bookrent_thread = threading.Thread(target=check_for_new_bookrent_messages, daemon=True)
        bookrent_thread.start()

    # Start the background thread for checking new book rent messages
    def start_checking_new_messages():
        bookrent_thread = threading.Thread(target=check_for_new_messages, daemon=True)
        bookrent_thread.start()
        
        
    # Start both threads on bot initialization
    start_checking_new_announcements()
    start_checking_new_bookrent_messages()
    start_checking_new_messages()
    
    ###########################################################################################################################################################
    ########################################################                                 ##################################################################
    ######################################################## E-BOOK request and handler code ##################################################################
    ########################################################                                 ##################################################################
    ###########################################################################################################################################################
    
    @bot.message_handler(func=lambda message: message.text.isalnum())
    @ensure_registered
    def send_book(message):
        book_id = message.text
        chat_id = message.chat.id
        message_id = message.message_id
        telegram_id = message.from_user.id
        user_language = get_user_language(telegram_id)
        response = responses[user_language]['book_not_found']

        # Send a loading message to indicate that the search is in progress
        loading_message = bot.send_message(chat_id, "Searching for your book, please wait...")

        conn = get_db_connection()
        cursor = conn.cursor()
        cursor.execute("SELECT title, publisher, year_published, thumbnail, pdf_path FROM books WHERE id = %s", (book_id,))
        book = cursor.fetchone()
        conn.close()
        
        # Delete the ID message from both the client and the bot's chat
        bot.delete_message(chat_id, message_id)

        # Delete the loading message
        bot.delete_message(chat_id, loading_message.message_id)

        if book:
            title, publisher, year_published, thumbnail, pdf_path = book
            full_pdf_path = os.path.join(PDF_DIR, pdf_path)  # Adjust PDF_DIR accordingly
            full_thumbnail_path = os.path.join(PDF_DIR, thumbnail)
            
            with open(full_pdf_path, 'rb') as pdf_file:
                with open(full_thumbnail_path, 'rb') as thumb_file:
                    bot.send_document(chat_id, pdf_file, caption=f"Title: {title}\nPublisher: {publisher}\nDate of Publication: {year_published}", thumb=thumb_file, protect_content=True, timeout=70)
                    show_menu(message)
                    print("ID:", chat_id, "First Name:", message.from_user.first_name, "Downloaded book of title:", title)
        else:
            bot.send_message(chat_id, response)
            show_menu(message)



            

    bot.polling(none_stop=True)



if __name__ == "__main__":
    main()
