<?php
/*
 * ========================================================================================
 * DISCLAIMER ⚠️
 * ========================================================================================
 * This script was created strictly for educational purposes. Any unauthorized or illegal
 * activities conducted using this tool are solely the responsibility of the user.
 * The creator is not responsible for any misuse or damage caused by the use of this tool.
 *
 * Always ensure you have explicit permission before accessing or using systems.
 *
 * AUTHOR: uthman dev
 * LICENSE: GNU General Public License v3.0
 * ========================================================================================
 */

declare(strict_types=1);

class RaitroxServer {
    private string $host;
    private int $port;
    private string $responseDir;
    private string $formFile;
    private string $sshOutputFile;
    private string $inputFile;

    public function __construct(
        string $host = 'localhost',
        ?int $port = null,
        string $responseDir = 'response',
        string $formFile = 'index.html'
    ) {
        $this->host = $host;
        $this->port = $port ?? $this->getRandomPort();
        $this->responseDir = $responseDir;
        $this->formFile = $formFile;
        $this->sshOutputFile = "$responseDir/ssh_output.txt";
        $this->inputFile = "$responseDir/input.txt";
    }

    private function displayLogo(): void {
        echo "\033[38;5;196m"; // Bright red color
        echo "██████╗  █████╗ ██╗████████╗██████╗  ██████╗ ██╗  ██╗\n";
        echo "██╔══██╗██╔══██╗██║╚══██╔══╝██╔══██╗██╔═══██╗╚██╗██╔╝\n";
        echo "██████╔╝███████║██║   ██║   ██████╔╝██║   ██║ ╚███╔╝ \n";
        echo "██╔══██╗██╔══██║██║   ██║   ██╔══██╗██║   ██║ ██╔██╗ \n";
        echo "██████╔╝██║  ██║██║   ██║   ██████╔╝╚██████╔╝██╔╝ ██╗\n";
        echo "╚═════╝ ╚═╝  ╚═╝╚═╝   ╚═╝   ╚═════╝  ╚═════╝ ╚═╝  ╚═╝\n";
        echo "\033[0m\n"; // Reset color

        $this->displayColoredBox("Educational Purpose Only - Use Responsibly", "196");
        echo "\n";
    }

    private function displayColoredBox(string $text, string $colorCode): void {
        $length = strlen($text) + 4;
        $border = str_repeat("═", $length);
        
        echo "\033[38;5;{$colorCode}m"; // Set color
        echo "╔{$border}╗\n";
        echo "║  $text  ║\n";
        echo "╚{$border}╝\n";
        echo "\033[0m"; // Reset color
    }

    private function displayStatusMessage(string $message, string $type = 'info'): void {
        $colors = [
            'success' => '82',  // Light green
            'error' => '196',   // Light red
            'info' => '87',     // Cyan
            'warning' => '214'  // Orange
        ];
        
        $color = $colors[$type] ?? '15'; // Default to white
        $prefix = match($type) {
            'success' => '✓',
            'error' => '✗',
            'warning' => '⚠',
            default => 'ℹ'
        };
        
        echo "\033[38;5;{$color}m{$prefix} {$message}\033[0m\n";
    }

    private function getRandomPort(): int {
        return random_int(1024, 65535);
    }

    private function validateEnvironment(): void {
        if (php_sapi_name() !== 'cli') {
            throw new RuntimeException('This tool must be run from the command line.');
        }
    }

    private function setupEnvironment(): void {
        system(PHP_OS_FAMILY === 'Windows' ? 'cls' : 'clear');
        
        if (!file_exists($this->responseDir)) {
            mkdir($this->responseDir, 0755, true);
            $this->displayStatusMessage("Created response directory", 'success');
        }

        foreach ([$this->inputFile, $this->sshOutputFile] as $file) {
            if (!file_exists($file)) {
                touch($file);
                $this->displayStatusMessage("Created file: " . basename($file), 'success');
            }
        }

        if (!file_exists($this->formFile)) {
            throw new RuntimeException("Required file missing: {$this->formFile}");
        }
    }

    private function startServer(): float {
        $startTime = microtime(true);
        
        $command = sprintf(
            'php -S %s:%d > /dev/null 2>&1 & echo $!',
            $this->host,
            $this->port
        );
        
        $pid = shell_exec($command);
        
        if (empty($pid)) {
            throw new RuntimeException('Failed to start the server');
        }
        
        return (microtime(true) - $startTime) * 1000;
    }

    private function establishSSHTunnel(): ?string {
        $this->displayStatusMessage("Establishing SSH tunnel...", 'info');
        
        $ssh_command = "ssh -R 80:localhost:{$this->port} serveo.net > {$this->sshOutputFile} 2>&1 &";
        exec($ssh_command);

        $maxAttempts = 30;
        $attempts = 0;
        
        while ($attempts < $maxAttempts) {
            if (file_exists($this->sshOutputFile)) {
                $lines = file($this->sshOutputFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($lines as $line) {
                    if (strpos($line, 'http') !== false) {
                        return trim($line);
                    }
                }
            }
            $attempts++;
            sleep(1);
        }
        
        throw new RuntimeException('Failed to establish SSH tunnel after 30 seconds');
    }

    private function monitorInput(): void {
        $this->displayColoredBox("Input Monitoring Started", "87");
        $this->displayStatusMessage("Press Ctrl+C to stop", 'info');
        echo "\n";

        $lastCheck = 0;
        
        while (true) {
            clearstatcache();
            
            if (file_exists($this->inputFile)) {
                $currentTime = filemtime($this->inputFile);
                
                if ($currentTime > $lastCheck) {
                    $input = file_get_contents($this->inputFile);
                    
                    if (!empty($input)) {
                        echo "\n";
                        $this->displayColoredBox("New Input Received", "82");
                        echo "\033[38;5;82m" . $input . "\033[0m\n";
                        echo str_repeat("─", 50) . "\n";
                        
                        file_put_contents($this->inputFile, "");
                        $lastCheck = $currentTime;
                    }
                }
            }
            
            sleep(2);
        }
    }

    public function start(): void {
        try {
            $this->validateEnvironment();
            $this->displayLogo();
            $this->setupEnvironment();
            
            $startupTime = $this->startServer();
            $this->displayStatusMessage(
                sprintf("Server started (%.2f ms)", $startupTime),
                'success'
            );
            $this->displayStatusMessage(
                sprintf("Local URL: http://%s:%d", $this->host, $this->port),
                'info'
            );
            
            $tunnelUrl = $this->establishSSHTunnel();
            if ($tunnelUrl) {
                $this->displayStatusMessage("Tunnel established", 'success');
                $this->displayStatusMessage("Public URL: $tunnelUrl", 'info');
            }
            
            echo "\n";
            $this->monitorInput();
            
        } catch (Exception $e) {
            $this->displayStatusMessage("Error: " . $e->getMessage(), 'error');
            exit(1);
        }
    }
}

// Initialize and start the server
$server = new RaitroxServer();
$server->start();