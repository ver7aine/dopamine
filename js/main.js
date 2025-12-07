// Main JavaScript for interactive effects
document.addEventListener('DOMContentLoaded', function() {
    
    // 1. Mobile Navigation
    const hamburger = document.querySelector(".hamburger");
    const navMenu = document.querySelector(".nav-menu");
    
    hamburger?.addEventListener("click", () => {
        hamburger.classList.toggle("active");
        navMenu.classList.toggle("active");
    });
    
    document.querySelectorAll(".nav-link").forEach(n => n.addEventListener("click", () => {
        hamburger?.classList.remove("active");
        navMenu?.classList.remove("active");
    }));
    
    // 2. Header scroll effect
    window.addEventListener('scroll', () => {
        const header = document.querySelector('.header');
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
        
        // Fade in elements on scroll
        const fadeElements = document.querySelectorAll('.fade-in');
        fadeElements.forEach(element => {
            const elementTop = element.getBoundingClientRect().top;
            const windowHeight = window.innerHeight;
            if (elementTop < windowHeight - 100) {
                element.classList.add('visible');
            }
        });
    });
    
    // 3. Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 100,
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // 4. Menu filtering with animation
    const filterButtons = document.querySelectorAll('.filter-btn');
    const menuItems = document.querySelectorAll('.menu-item');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Remove active class from all buttons
            filterButtons.forEach(btn => btn.classList.remove('active'));
            // Add active class to clicked button
            button.classList.add('active');
            
            const filterValue = button.getAttribute('data-filter');
            
            // Animate items out
            menuItems.forEach(item => {
                item.style.opacity = '0';
                item.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    if (filterValue === 'all' || item.classList.contains(filterValue)) {
                        item.style.display = 'block';
                        setTimeout(() => {
                            item.style.opacity = '1';
                            item.style.transform = 'translateY(0)';
                        }, 50);
                    } else {
                        item.style.display = 'none';
                    }
                }, 300);
            });
        });
    });
    
    // 5. Testimonial slider
    const testimonialsContainer = document.querySelector('.testimonials-container');
    const testimonialCards = document.querySelectorAll('.testimonial-card');
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');
    
    if (testimonialsContainer && testimonialCards.length > 1) {
        let currentIndex = 0;
        const cardWidth = testimonialCards[0].offsetWidth + 30;
        
        function updateSlider() {
            testimonialsContainer.style.transform = `translateX(-${currentIndex * cardWidth}px)`;
            
            // Add active class to current card
            testimonialCards.forEach((card, index) => {
                card.classList.toggle('active', index === currentIndex);
            });
        }
        
        nextBtn?.addEventListener('click', () => {
            if (currentIndex < testimonialCards.length - 1) {
                currentIndex++;
                updateSlider();
            }
        });
        
        prevBtn?.addEventListener('click', () => {
            if (currentIndex > 0) {
                currentIndex--;
                updateSlider();
            }
        });
        
        // Auto slide
        setInterval(() => {
            if (currentIndex < testimonialCards.length - 1) {
                currentIndex++;
            } else {
                currentIndex = 0;
            }
            updateSlider();
        }, 5000);
    }
    
    // 6. Cursor follower
    const cursor = document.createElement('div');
    cursor.className = 'cursor-follower';
    document.body.appendChild(cursor);
    
    document.addEventListener('mousemove', (e) => {
        cursor.style.left = e.clientX + 'px';
        cursor.style.top = e.clientY + 'px';
    });
    
    document.querySelectorAll('button, a, .menu-item, .feature').forEach(el => {
        el.addEventListener('mouseenter', () => {
            cursor.classList.add('active');
        });
        
        el.addEventListener('mouseleave', () => {
            cursor.classList.remove('active');
        });
    });
    
    // 7. Newsletter form
    const newsletterForm = document.getElementById('newsletter-form');
    newsletterForm?.addEventListener('submit', function(e) {
        e.preventDefault();
        const email = this.querySelector('input[type="email"]').value;
        
        // Animation
        const submitBtn = this.querySelector('button');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-check"></i>';
        submitBtn.style.background = '#4CAF50';
        
        // Reset after 2 seconds
        setTimeout(() => {
            this.reset();
            submitBtn.innerHTML = originalText;
            submitBtn.style.background = '';
            
            // Show success message
            const successMsg = document.createElement('div');
            successMsg.className = 'success-message';
            successMsg.innerHTML = '<i class="fas fa-check-circle"></i> Terima kasih telah berlangganan!';
            successMsg.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: #4CAF50;
                color: white;
                padding: 15px 25px;
                border-radius: 10px;
                box-shadow: 0 10px 25px rgba(0,0,0,0.2);
                z-index: 10000;
                animation: slideInRight 0.5s;
            `;
            document.body.appendChild(successMsg);
            
            setTimeout(() => {
                successMsg.style.animation = 'slideOutRight 0.5s forwards';
                setTimeout(() => successMsg.remove(), 500);
            }, 3000);
        }, 2000);
    });
    
    // 8. Add coffee beans animation to hero
    const heroSection = document.querySelector('.hero');
    if (heroSection) {
        const coffeeBeans = document.createElement('div');
        coffeeBeans.className = 'coffee-beans';
        
        for (let i = 0; i < 6; i++) {
            const bean = document.createElement('div');
            bean.className = 'coffee-bean';
            bean.innerHTML = '☕';
            coffeeBeans.appendChild(bean);
        }
        
        heroSection.appendChild(coffeeBeans);
        
        // Add coffee steam
        const coffeeSteam = document.createElement('div');
        coffeeSteam.className = 'coffee-steam';
        for (let i = 0; i < 3; i++) {
            const steam = document.createElement('div');
            steam.className = 'steam';
            coffeeSteam.appendChild(steam);
        }
        heroSection.appendChild(coffeeSteam);
    }
    
    // 9. Add particles to special sections
    const sections = document.querySelectorAll('.hero, .menu, .testimonials');
    sections.forEach(section => {
        if (section.classList.contains('particles')) return;
        
        const particles = document.createElement('div');
        particles.className = 'particles';
        section.appendChild(particles);
        
        // Create particles
        for (let i = 0; i < 20; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.cssText = `
                left: ${Math.random() * 100}%;
                width: ${Math.random() * 10 + 2}px;
                height: ${Math.random() * 10 + 2}px;
                background: var(${Math.random() > 0.5 ? '--primary-color' : '--secondary-color'});
                animation-delay: ${Math.random() * 15}s;
                animation-duration: ${Math.random() * 10 + 10}s;
            `;
            particles.appendChild(particle);
        }
    });
    
    // 10. Add floating button
    const floatBtn = document.createElement('a');
    floatBtn.href = '#menu';
    floatBtn.className = 'btn-float';
    floatBtn.innerHTML = '<i class="fas fa-arrow-up"></i>';
    document.body.appendChild(floatBtn);
    
    // Scroll to top behavior
    window.addEventListener('scroll', () => {
        if (window.scrollY > 500) {
            floatBtn.innerHTML = '<i class="fas fa-arrow-up"></i>';
            floatBtn.href = '#';
            floatBtn.onclick = (e) => {
                e.preventDefault();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            };
        } else {
            floatBtn.innerHTML = '<i class="fas fa-coffee"></i>';
            floatBtn.href = '#menu';
            floatBtn.onclick = null;
        }
    });
    
    // 11. Initialize fade-in elements
    const fadeElements = document.querySelectorAll('.menu-item, .event-card, .feature, .contact-item');
    fadeElements.forEach(el => {
        el.classList.add('fade-in');
    });
    
    // Trigger initial scroll check
    window.dispatchEvent(new Event('scroll'));
    
    // 12. Add confetti effect on click (optional)
    document.addEventListener('click', function(e) {
        if (e.target.tagName === 'BUTTON' || e.target.closest('button')) {
            createConfetti(e.clientX, e.clientY);
        }
    });
    
    function createConfetti(x, y) {
        const colors = ['#6d4c41', '#a1887f', '#d7ccc8', '#ff9800', '#4CAF50'];
        
        for (let i = 0; i < 10; i++) {
            const confetti = document.createElement('div');
            confetti.style.cssText = `
                position: fixed;
                width: 10px;
                height: 10px;
                background: ${colors[Math.floor(Math.random() * colors.length)]};
                border-radius: 50%;
                top: ${y}px;
                left: ${x}px;
                pointer-events: none;
                z-index: 10000;
                transform: translate(0, 0) rotate(0deg);
                animation: confettiFall 1s forwards;
            `;
            
            document.body.appendChild(confetti);
            
            setTimeout(() => {
                confetti.remove();
            }, 1000);
        }
    }
    
    // Add confetti animation to CSS
    const style = document.createElement('style');
    style.textContent = `
        @keyframes confettiFall {
            0% {
                transform: translate(0, 0) rotate(0deg);
                opacity: 1;
            }
            100% {
                transform: translate(${Math.random() * 200 - 100}px, ${window.innerHeight}px) rotate(${Math.random() * 720}deg);
                opacity: 0;
            }
        }
        
        @keyframes slideOutRight {
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
    
    // 13. Typing effect for hero title (optional)
    const heroTitle = document.querySelector('.hero-title');
    if (heroTitle && !heroTitle.dataset.animated) {
        const text = heroTitle.textContent;
        heroTitle.textContent = '';
        heroTitle.dataset.animated = 'true';
        
        let i = 0;
        function typeWriter() {
            if (i < text.length) {
                heroTitle.textContent += text.charAt(i);
                i++;
                setTimeout(typeWriter, 50);
            }
        }
        setTimeout(typeWriter, 1000);
    }
});