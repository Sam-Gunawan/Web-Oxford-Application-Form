document.querySelectorAll('.toggle-btn').forEach(button => {
    const icon = button.querySelector('i');
    const targetId = button.getAttribute('data-bs-target');
    const target = document.querySelector(targetId);
  
    target.addEventListener('shown.bs.collapse', () => {
      icon.classList.remove('fa-caret-down');
      icon.classList.add('fa-caret-up');
    });
  
    target.addEventListener('hidden.bs.collapse', () => {
      icon.classList.remove('fa-caret-up');
      icon.classList.add('fa-caret-down');
    });
  });
  