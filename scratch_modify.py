import os
import re

directory = 'c:/Users/Sintia/web_anjalai/database/migrations'

for filename in os.listdir(directory):
    if filename.endswith('.php'):
        filepath = os.path.join(directory, filename)
        with open(filepath, 'r', encoding='utf-8') as f:
            content = f.read()
        
        # Replace $table->string('something') with $table->string('something', 25)
        # Exclude 'password' and 'email' because they break the app if truncated
        # Exclude 'id' and 'token' in the users table because they might be longer
        pattern = r"\$table->string\('((?!password|email|id|token|remember_token|foto|bukti_pembayaran)[^']+)'\)"
        new_content = re.sub(pattern, r"$table->string('\1', 25)", content)
        
        if content != new_content:
            with open(filepath, 'w', encoding='utf-8') as f:
                f.write(new_content)
            print(f"Updated {filename}")
