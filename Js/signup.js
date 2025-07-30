document.getElementById('signupForm').addEventListener('submit', function (e) {
    e.preventDefault();
  
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    const passwordError = document.getElementById('passwordError');
    const confirmError = document.getElementById('confirmError');
  
    passwordError.textContent = '';
    confirmError.textContent = '';
  
    const passwordCriteria = /^(?=.*[A-Z])(?=.*\d).{8,}$/;
  
    if (!passwordCriteria.test(password)) {
      passwordError.textContent = 'Password does not meet the criteria.';
      return;
    }
  
    if (password !== confirmPassword) {
      confirmError.textContent = 'Passwords do not match.';
      return;
    }
  
    alert('Account created successfully!');
    // You can redirect or save user info here
  });
  