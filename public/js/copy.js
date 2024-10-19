document.addEventListener('DOMContentLoaded', function() {
    const copyElements = document.querySelectorAll('.c');
    copyElements.forEach(function(element) {
      element.addEventListener('click', function() {
        const text = element.previousElementSibling.textContent;
        navigator.clipboard.writeText(text).then(function() {
          console.log('Text copied to clipboard');
        });
      });
    });
  });
