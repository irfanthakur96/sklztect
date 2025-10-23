/***************************************************
 ====================  JS INDEX ====================== 
****************************************************
01. Mobile menu Start
02. Mobile offcanvas
****************************************************/

(function ($) {
  "use strict";  
  // Using an object literal for a jQuery Hello Animation Theme module
  var hello_animation_theme_module = {
  
    init: function (settings) {
      hello_animation_theme_module.config = {
        responsive_menu_width: 1199,       
        header : $(".default-blog-header"),
      };
        // Allow overriding the default config
      $.extend(hello_animation_theme_module.config, settings);
      hello_animation_theme_module.setup();
    },
    // Call all init method here
    setup: function () { 

      if(document.getElementById('hello-animation-openOffcanvas')){
        hello_animation_theme_module.offcanvas();  
      }
      
    },
 
    offcanvas: function(){
         // Get the offcanvas element and buttons
       const offcanvas = document.getElementById('hello-animation-offcanvas');
       const openBtn   = document.getElementById('hello-animation-openOffcanvas');
       const closeBtn  = document.getElementById('hello-animation-closeOffcanvas');
       
         // Function to open the offcanvas
       openBtn.addEventListener('click', () => {
           offcanvas.classList.add('show');
       });
       
         // Function to close the offcanvas
       closeBtn.addEventListener('click', () => {
           offcanvas.classList.remove('show');
       });
       
         // Close offcanvas when clicking outside of it
       document.addEventListener('click', (event) => {
           const isClickInside = offcanvas.contains(event.target) || openBtn.contains(event.target);
           if (!isClickInside) {
               offcanvas.classList.remove('show');
           }
       }); 
      
       
          // Get all menu items with potential submenus
          const menuItems = document.querySelectorAll('.hello-animation-mb-menu-items li > a');
        
          // Loop through each menu item to add click event listener
          menuItems.forEach(menuItem => {
              const default_icon = menuItem.nextElementSibling;
                if (default_icon && default_icon.tagName === 'UL' && default_icon.classList.contains('dp-menu')) {
                    menuItem.querySelector('.nav-direction-icon').setAttribute('data-icon', '+')
                    
                
            
                    menuItem.querySelector('.nav-direction-icon').addEventListener('click', function (e) {
                    const submenu = this.parentElement.nextElementSibling; // Get the next sibling, which should be the submenu <ul>
                    
                    // Check if this next sibling is a submenu (<ul>)
                    if (submenu && submenu.tagName === 'UL' && submenu.classList.contains('dp-menu')) {
                        e.preventDefault(); // Prevent the default link behavior (navigating)
                
                        // Toggle aria-expanded attribute for accessibility
                        const expanded = submenu.getAttribute('aria-expanded') === 'true';
                        submenu.setAttribute('aria-expanded', !expanded);
                
                        // Toggle the display of the submenu (open/close)
                        submenu.style.display = expanded ? 'none' : 'block';
                
                        // Toggle + or - symbol
                        if (expanded) {
                        this.setAttribute('data-icon', '+');
                        } else {
                        this.setAttribute('data-icon', '-');
                        }
                    }
                    });
                }
          });
        
          // Accessibility: Enable keyboard navigation
          document.querySelectorAll('.hello-animation-mb-menu-items a').forEach(link => {
            link.addEventListener('keydown', function (event) {
              if (event.key === 'ArrowDown') {
                event.preventDefault();
                let nextItem = event.target.parentElement.nextElementSibling;
                if (nextItem) nextItem.querySelector('a').focus();
              }
              if (event.key === 'ArrowUp') {
                event.preventDefault();
                let prevItem = event.target.parentElement.previousElementSibling;
                if (prevItem) prevItem.querySelector('a').focus();
              }
            });
          });     
        
                  
    }
    
  };
  $(document).ready(hello_animation_theme_module.init);

})(jQuery);






