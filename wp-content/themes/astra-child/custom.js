/* Fuzzy Furniture Store - Custom JS */
(function($) {
    'use strict';

    // ===== HERO SLIDER =====
    let currentSlide = 0;
    const slides = document.getElementById('heroSlides');
    const dotsContainer = document.getElementById('heroDots');

    function initSlider() {
        if (!slides) return;
        const totalSlides = slides.children.length;
        
        // Create dots
        if (dotsContainer) {
            for (let i = 0; i < totalSlides; i++) {
                const dot = document.createElement('span');
                dot.className = 'dot' + (i === 0 ? ' active' : '');
                dot.onclick = function() { goToSlide(i); };
                dotsContainer.appendChild(dot);
            }
        }

        // Auto slide
        setInterval(function() { moveSlide(1); }, 5000);
    }

    window.moveSlide = function(direction) {
        if (!slides) return;
        const totalSlides = slides.children.length;
        currentSlide = (currentSlide + direction + totalSlides) % totalSlides;
        updateSlider();
    };

    window.goToSlide = function(index) {
        currentSlide = index;
        updateSlider();
    };

    function updateSlider() {
        if (!slides) return;
        slides.style.transform = 'translateX(-' + (currentSlide * 100) + '%)';
        
        // Update dots
        if (dotsContainer) {
            const dots = dotsContainer.querySelectorAll('.dot');
            dots.forEach(function(dot, i) {
                dot.classList.toggle('active', i === currentSlide);
            });
        }
    }

    // ===== LIVE SEARCH =====
    let searchTimeout;
    function initSearch() {
        const input = document.getElementById('fuzzy-search-input');
        const results = document.getElementById('fuzzy-search-results');
        
        if (!input || !results) return;

        input.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            
            if (query.length < 2) {
                results.classList.remove('active');
                results.innerHTML = '';
                return;
            }

            searchTimeout = setTimeout(function() {
                $.ajax({
                    url: fuzzy_ajax.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'fuzzy_live_search',
                        query: query
                    },
                    success: function(data) {
                        if (data.length > 0) {
                            let html = '';
                            data.forEach(function(item) {
                                const img = item.image || 'https://via.placeholder.com/50';
                                html += '<a href="' + item.url + '" class="fuzzy-search-item">';
                                html += '<img src="' + img + '" alt="' + item.title + '" />';
                                html += '<div class="item-info">';
                                html += '<div class="item-title">' + item.title + '</div>';
                                html += '<div class="item-price">' + item.price + '</div>';
                                html += '</div></a>';
                            });
                            results.innerHTML = html;
                            results.classList.add('active');
                        } else {
                            results.innerHTML = '<div style="padding:15px;color:#888;text-align:center;">Không tìm thấy sản phẩm</div>';
                            results.classList.add('active');
                        }
                    }
                });
            }, 300);
        });

        // Close search results on click outside
        document.addEventListener('click', function(e) {
            if (!input.contains(e.target) && !results.contains(e.target)) {
                results.classList.remove('active');
            }
        });

        // Search on Enter
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const query = this.value.trim();
                if (query) {
                    window.location.href = '/?s=' + encodeURIComponent(query) + '&post_type=product';
                }
            }
        });

        // Search button click
        var searchBtn = document.getElementById('fuzzy-search-btn');
        if (searchBtn) {
            searchBtn.addEventListener('click', function() {
                var query = input.value.trim();
                if (query) {
                    window.location.href = '/?s=' + encodeURIComponent(query) + '&post_type=product';
                }
            });
        }
    }

    // ===== INIT =====
    $(document).ready(function() {
        initSlider();
        initSearch();
    });

})(jQuery);
