<?php
/**
 * File: app/views/shares/chat_widget.php
 * Tích hợp Chat Zalo, Facebook Messenger, Phone
 * 
 * Cách sử dụng: Include file này vào footer.php
 * <?php include 'app/views/shares/chat_widget.php'; ?>
 */
?>

<style>
/* Chat Widget Container */
.chat-widget-container {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    gap: 15px;
    align-items: flex-end;
}

/* Chat Button Base Style */
.chat-button {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transition: all 0.3s ease;
    text-decoration: none;
    position: relative;
    animation: pulse 2s infinite;
}

.chat-button:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
}

/* Zalo Button */
.zalo-button {
    background: linear-gradient(135deg, #0068FF 0%, #0084FF 100%);
}

.zalo-button::before {
    content: '';
    width: 35px;
    height: 35px;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 460.1 436.6'%3E%3Cpath fill='%23FFF' d='M82.6 380.9c-1.8-.8-3.1-1.7-1-3.5 1.3-1 2.7-1.9 4.1-2.8 13.1-8.5 25.4-17.8 33.5-31.5 6.8-11.4 5.7-18.1-2.8-26.5C69 269.2 48.2 212.5 58.6 145.5 64.5 107.7 81.8 75 107 46.6c15.2-17.2 33.3-31.1 53.1-42.7 1.2-.7 2.9-.9 3.1-2.7-.4-1-1.1-.7-1.7-.7-33.7 0-67.4-.7-101 .2C28.3 1.7.5 26.6.6 62.3c.2 104.3 0 208.6 0 313 0 32.4 24.7 59.5 57 60.7 27.3 1.1 54.6.2 82 .1 2 .1 4 .2 6 .2H290c36 0 72 .2 108 0 33.4 0 60.5-27 60.5-60.3v-.6-21.5c-1.4 0-2.2.5-3.1.7-11.2 3.1-21.2 8.6-29.9 16.5-16.9 15.5-18.7 34.2-16.1 55.4 0 .4.1.9.1 1.3v.6c0 17.5-14.3 31.8-31.8 31.8H142.6c-17.5 0-31.8-14.3-31.8-31.8v-.6-64.9c0-17.5 14.3-31.7 31.8-31.7h74.9c1.4 0 2.7-.3 4.1-.4 7.2-.4 13.8-2.9 20-6.8 12.3-7.9 25.1-14.8 38.2-21 .3-.2.6-.4.9-.6.6-.4 1.2-.8 1.8-1.2 11.3-7.5 22.6-14.9 33.9-22.3 1.8-1.2 1.8-1.2 1.8-3.6v-115.4c0-17.5-14.3-31.7-31.8-31.7H142.6c-17.5 0-31.8 14.3-31.8 31.7V360.8c0 2.6-1.1 4.3-3 6.1-4.9 4.6-8.9 9.8-11.8 15.8-.6 1.2-1.2 2.4-1.8 3.6-.8 1.6-1.6 3.2-2.3 4.8z'/%3E%3C/svg%3E");
    background-size: contain;
    background-repeat: no-repeat;
    display: block;
}

/* Facebook Messenger Button */
.messenger-button {
    background: linear-gradient(135deg, #00B2FF 0%, #006AFF 100%);
}

.messenger-button i {
    font-size: 30px;
    color: white;
}

/* Phone Button */
.phone-button {
    background: linear-gradient(135deg, #00A74F 0%, #00c853 100%);
}

.phone-button i {
    font-size: 28px;
    color: white;
    animation: shake 1s infinite;
}

/* Tooltip */
.chat-tooltip {
    position: absolute;
    right: 75px;
    background: white;
    color: #333;
    padding: 8px 15px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 600;
    white-space: nowrap;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease;
}

.chat-button:hover .chat-tooltip {
    opacity: 1;
}

/* Pulse Animation */
@keyframes pulse {
    0%, 100% {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15), 0 0 0 0 rgba(0, 168, 255, 0.7);
    }
    50% {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15), 0 0 0 10px rgba(0, 168, 255, 0);
    }
}

/* Shake Animation for Phone */
@keyframes shake {
    0%, 100% { transform: rotate(0deg); }
    10%, 30%, 50%, 70%, 90% { transform: rotate(-10deg); }
    20%, 40%, 60%, 80% { transform: rotate(10deg); }
}

/* Bounce In Animation */
@keyframes bounceIn {
    0% {
        opacity: 0;
        transform: scale(0.3) translateY(100px);
    }
    50% {
        opacity: 1;
        transform: scale(1.05);
    }
    70% {
        transform: scale(0.9);
    }
    100% {
        transform: scale(1);
    }
}

.chat-button {
    animation: bounceIn 0.6s ease-out, pulse 2s infinite 1s;
}

.chat-button:nth-child(2) {
    animation-delay: 0.1s;
}

.chat-button:nth-child(3) {
    animation-delay: 0.2s;
}

/* Online Badge */
.online-badge {
    position: absolute;
    top: 5px;
    right: 5px;
    width: 14px;
    height: 14px;
    background: #4CAF50;
    border: 2px solid white;
    border-radius: 50%;
    animation: blink 2s infinite;
}

@keyframes blink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

/* Responsive */
@media (max-width: 768px) {
    .chat-widget-container {
        bottom: 15px;
        right: 15px;
    }

    .chat-button {
        width: 55px;
        height: 55px;
    }

    .messenger-button i,
    .phone-button i {
        font-size: 26px;
    }

    .chat-tooltip {
        display: none;
    }
}
</style>

<!-- Chat Widget Container -->
<div class="chat-widget-container">
    <!-- Zalo Button -->
    <a href="https://zalo.me/0346024870" 
       target="_blank" 
       class="chat-button zalo-button"
       title="Chat với chúng tôi qua Zalo">
        <span class="online-badge"></span>
        <span class="chat-tooltip">Chat qua Zalo</span>
    </a>

    <!-- Facebook Messenger Button -->
    <a href="https://www.facebook.com/onguyen.40148" 
       target="_blank" 
       class="chat-button messenger-button"
       title="Nhắn tin qua Facebook Messenger">
        <span class="online-badge"></span>
        <i class="bi bi-messenger"></i>
        <span class="chat-tooltip">Chat qua Messenger</span>
    </a>

    <!-- Phone Button -->
    <a href="tel:0865399086" 
       class="chat-button phone-button"
       title="Gọi điện thoại ngay">
        <i class="bi bi-telephone-fill"></i>
        <span class="chat-tooltip">0346024870</span>
    </a>
</div>

<script>
// Thêm hiệu ứng khi click
document.querySelectorAll('.chat-button').forEach(button => {
    button.addEventListener('click', function(e) {
        // Tạo hiệu ứng ripple
        const ripple = document.createElement('div');
        ripple.style.position = 'absolute';
        ripple.style.borderRadius = '50%';
        ripple.style.background = 'rgba(255, 255, 255, 0.6)';
        ripple.style.width = '100%';
        ripple.style.height = '100%';
        ripple.style.top = '0';
        ripple.style.left = '0';
        ripple.style.transform = 'scale(0)';
        ripple.style.transition = 'transform 0.6s ease-out, opacity 0.6s ease-out';
        ripple.style.opacity = '1';
        
        this.appendChild(ripple);
        
        setTimeout(() => {
            ripple.style.transform = 'scale(2)';
            ripple.style.opacity = '0';
        }, 10);
        
        setTimeout(() => {
            ripple.remove();
        }, 600);
    });

    // Tracking click (Google Analytics - tùy chọn)
    button.addEventListener('click', function() {
        const platform = this.classList.contains('zalo-button') ? 'Zalo' :
                        this.classList.contains('messenger-button') ? 'Messenger' : 'Phone';
        
        // Nếu có Google Analytics
        if (typeof gtag !== 'undefined') {
            gtag('event', 'click', {
                'event_category': 'Contact',
                'event_label': platform
            });
        }
        
        console.log('User clicked:', platform);
    });
});

// Tự động ẩn/hiện khi scroll
let lastScrollTop = 0;
window.addEventListener('scroll', function() {
    const chatWidget = document.querySelector('.chat-widget-container');
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    
    if (scrollTop > lastScrollTop && scrollTop > 300) {
        chatWidget.style.opacity = '0.7';
    } else {
        chatWidget.style.opacity = '1';
    }
    lastScrollTop = scrollTop;
});
</script>