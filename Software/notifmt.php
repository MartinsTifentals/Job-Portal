<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class OnScreenNotifier {
    public static function addNotification($type, $title, $message, $timeout = 5000) {
        $notification = [
            'id' => uniqid(),
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'timeout' => $timeout,
            'timestamp' => time()
        ];

        $_SESSION['notifications'][] = $notification;
    }

    public static function getNotifications() {
        $notifications = $_SESSION['notifications'] ?? [];
        $_SESSION['notifications'] = [];
        return $notifications;
    }

    public static function clearNotifications() {
        unset($_SESSION['notifications']);
    }
}

function renderNotifications() {
    $notifications = OnScreenNotifier::getNotifications();
    if (empty($notifications)) {
        return;
    }
    ?>
    <style>
        .notification-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 350px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .notification {
            background: #f6f0ff;
            border-radius: 16px;
            padding: 18px;
            margin-bottom: 14px;
            box-shadow: 0 10px 30px rgba(84, 56, 177, 0.12);
            border-left: 6px solid #7c3aed;
            transform: translateX(400px);
            animation: slideIn 0.3s ease-out forwards;
            position: relative;
            opacity: 0;
        }

        .notification.show {
            opacity: 1;
        }

        @keyframes slideIn {
            to {
                transform: translateX(0);
            }
        }

        .notification.success {
            border-left-color: #8b5cf6;
            background: #f3e8ff;
            color: #4c1d95;
        }

        .notification.error {
            border-left-color: #a855f7;
            background: #f9f5ff;
            color: #5b21b6;
        }

        .notification.warning {
            border-left-color: #c084fc;
            background: #f8f0ff;
            color: #5b21b6;
        }

        .notification.info {
            border-left-color: #7c3aed;
            background: #ede9fe;
            color: #4c1d95;
        }

        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 10px;
        }

        .notification-title-row {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .notification-icon {
            width: 28px;
            height: 28px;
            object-fit: contain;
            border-radius: 8px;
            background: rgba(124, 58, 237, 0.1);
            padding: 4px;
        }

        .notification-title {
            font-weight: 700;
            font-size: 15px;
            margin: 0;
        }

        .notification-time {
            font-size: 11px;
            opacity: 0.7;
            margin-top: 8px;
        }

        .notification-message {
            font-size: 14px;
            line-height: 1.4;
            margin: 0 0 8px 0;
        }

        .notification-close {
            background: none;
            border: none;
            font-size: 18px;
            cursor: pointer;
            color: #4c1d95;
            padding: 0;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: background 0.2s;
        }

        .notification-close:hover {
            background: rgba(124, 58, 237, 0.15);
        }

        .notification-progress {
            height: 3px;
            background: rgba(0,0,0,0.2);
            border-radius: 2px;
            overflow: hidden;
            margin-top: 8px;
        }

        .notification-progress-bar {
            height: 100%;
            background: currentColor;
            width: 100%;
            transition: width linear;
            border-radius: 2px;
        }
    </style>

    <div class="notification-container" id="notificationContainer"></div>

    <script>
        const notifications = <?php echo json_encode($notifications); ?>;

        document.addEventListener('DOMContentLoaded', function() {
            if (notifications.length) {
                displayNotifications(notifications);
            }
        });

        function displayNotifications(notifications) {
            const container = document.getElementById('notificationContainer');

            notifications.forEach(notification => {
                const div = createNotificationElement(notification);
                container.appendChild(div);

                const timeout = notification.timeout;
                let progressWidth = 100;
                const progressBar = div.querySelector('.notification-progress-bar');

                const interval = setInterval(() => {
                    progressWidth -= (100 / (timeout / 100));
                    if (progressBar) progressBar.style.width = progressWidth + '%';
                }, 100);

                setTimeout(() => {
                    clearInterval(interval);
                    div.remove();
                }, timeout);
            });
        }

        function createNotificationElement(notification) {
            const div = document.createElement('div');
            div.className = `notification ${notification.type}`;

            const header = document.createElement('div');
            header.className = 'notification-header';

const titleRow = document.createElement('div');
        titleRow.className = 'notification-title-row';

        const icon = document.createElement('img');
        icon.className = 'notification-icon';
        icon.src = '/Job_Portal/assets/images/notification-icon.png';
        icon.alt = '';

        const title = document.createElement('h4');
        title.className = 'notification-title';
        title.textContent = notification.title;

        titleRow.appendChild(icon);
        titleRow.appendChild(title);

        const closeBtn = document.createElement('button');
        closeBtn.className = 'notification-close';
        closeBtn.innerHTML = '&times;';
        closeBtn.onclick = () => div.remove();

        header.appendChild(titleRow);
            header.appendChild(closeBtn);

            const message = document.createElement('p');
            message.className = 'notification-message';
            message.textContent = notification.message;

            const time = document.createElement('div');
            time.className = 'notification-time';
            time.textContent = new Date(notification.timestamp * 1000).toLocaleTimeString();

            const progress = document.createElement('div');
            progress.className = 'notification-progress';

            const progressBar = document.createElement('div');
            progressBar.className = 'notification-progress-bar';
            progress.appendChild(progressBar);

            div.appendChild(header);
            div.appendChild(message);
            div.appendChild(time);
            div.appendChild(progress);

            setTimeout(() => div.classList.add('show'), 50);
            return div;
        }
    </script>
    <?php
}

// Job Portal file: notifmt.php
