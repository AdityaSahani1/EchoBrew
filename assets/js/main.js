// Echoes & Brews - Main JavaScript

// Theme Toggle
document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.getElementById('theme-toggle');
    const body = document.body;
    
    // Load saved theme
    const savedTheme = getCookie('theme') || 'dark';
    body.className = savedTheme;
    
    if (themeToggle) {
        themeToggle.addEventListener('click', function() {
            const currentTheme = body.classList.contains('dark') ? 'dark' : 'light';
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            body.className = newTheme;
            setCookie('theme', newTheme, 365);
        });
    }
    
    // Mood Filter Buttons
    const moodButtons = document.querySelectorAll('.mood-btn');
    moodButtons.forEach(button => {
        button.addEventListener('click', function() {
            const mood = this.dataset.mood;
            window.location.href = `cafe.php?mood=${mood}`;
        });
    });
    
    // Mobile Menu Toggle
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const navMenu = document.querySelector('.nav-menu');
    
    if (mobileMenuToggle && navMenu) {
        mobileMenuToggle.addEventListener('click', function() {
            navMenu.style.display = navMenu.style.display === 'flex' ? 'none' : 'flex';
        });
    }
});

// Reaction System
async function toggleReaction(postId, type) {
    try {
        const response = await fetch('api/reaction.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ post_id: postId, type: type })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Update reaction count in UI
            const reactionElement = document.querySelector(`[data-post="${postId}"][data-type="${type}"] .count`);
            if (reactionElement) {
                reactionElement.textContent = data.count;
            }
            
            // Toggle active state
            const reactionButton = document.querySelector(`[data-post="${postId}"][data-type="${type}"]`);
            if (reactionButton) {
                reactionButton.classList.toggle('active', data.reacted);
            }
        }
    } catch (error) {
        console.error('Error toggling reaction:', error);
    }
}

// Cookie Helper Functions
function setCookie(name, value, days) {
    const expires = new Date();
    expires.setTime(expires.getTime() + days * 24 * 60 * 60 * 1000);
    document.cookie = `${name}=${value};expires=${expires.toUTCString()};path=/`;
}

function getCookie(name) {
    const nameEQ = name + "=";
    const ca = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

// Post Preview Animation
document.querySelectorAll('.post-card').forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-8px) rotate(0.5deg)';
    });
    
    card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0) rotate(0)';
    });
});

// Smooth Scroll
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth' });
        }
    });
});
