// Login Form Validation
function validateLoginForm(event) {
    event.preventDefault();
    
    const email = document.querySelector('input[type="email"]').value;
    const password = document.querySelector('input[type="password"]').value;
    
    // Email validation
    if (!email) {
        alert('Please enter your email');
        return false;
    }
    
    if (!email.includes('utem.edu.my')) {
        alert('Please use a valid UTeM email address');
        return false;
    }
    
    // Password validation
    if (!password) {
        alert('Please enter your password');
        return false;
    }
    
    if (password.length < 8) {
        alert('Password must be at least 8 characters long');
        return false;
    }

    // If all validations pass, submit the form to the PHP backend (login.php)
    const form = document.querySelector('form');
    const formData = new FormData(form);
    formData.append('email', email);
    formData.append('password', password);

    fetch('login.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())  // Assuming the response is JSON
    .then(data => {
        if (data.success) {
            if (data.redirect) {
                window.location.href = data.redirect;  // Redirect to the appropriate page
            }
        } else {
            alert(data.message);  // Show error message
        }
    })
    .catch(error => {
        alert('An error occurred: ' + error);
    });
}


// Signup Form Validation
function validateSignupForm(event) {
    event.preventDefault();
    
    const fullName = document.getElementById('fullName').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    const role = document.getElementById('role').value;
    
    // Name validation
    if (!fullName || fullName.length < 3) {
        alert('Please enter a valid full name (minimum 3 characters)');
        return false;
    }
    
    // Email validation
    if (!email || !email.includes('@','utem.edu.my')) {
        alert('Please use a valid UTeM email address');
        return false;
    }
    
    // Password validation
    if (password.length < 8) {
        alert('Password must be at least 8 characters long');
        return false;
    }
    
    if (password !== confirmPassword) {
        alert('Passwords do not match');
        return false;
    }
    
    // Role-specific validations
    if (role === 'student') {
        const studentId = document.getElementById('studentId').value;
        const kolej = document.getElementById('kolej').value;
        const room = document.getElementById('room').value;
        
        if (!studentId || !kolej || !room) {
            alert('Please fill in all student details');
            return false;
        }
        
        const roomPattern = /^[A-Z]{2}-[A-Z]-\d{1,2}-\d{2}-[A-Z]$/; //example AA-A-1-01-A
        if (!roomPattern.test(room)) {
            alert('Please enter a valid room number format and all capital letters (e.g., AA-A-1-01-A)');
            return false;
        }
    }
    
    window.location.href = 'home.html';
}

// Room Report Validation
document.getElementById("reportForm").addEventListener("submit", validateReportForm);

function validateReportForm(event) {
    event.preventDefault(); // Prevent default form submission

    const studentName = document.getElementById("studentName").value.trim();
    const roomNumber = document.getElementById("roomNumber").value.trim();
    const issue = document.getElementById("issue").value.trim();
    const description = document.getElementById("description").value.trim();

    if (!studentName || studentName.length < 3) {
        alert("Please provide your full name (at least 3 characters).");
        return;
    }

    if (!roomNumber) {
        alert("Please enter a valid room number.");
        return;
    }

    if (!issue) {
        alert("Please select an issue.");
        return;
    }

    if (!description || description.length < 10) {
        alert("Please provide a detailed description (minimum 10 characters).");
        return;
    }

    // Submit the form if all validations pass
    document.getElementById("reportForm").submit();
}


// Cleaner Task Assignment Validation
function validateCleanerForm(event) {
    event.preventDefault();
    
    const kolej = document.getElementById('kolej').value;
    const level = document.getElementById('level').value;
    const cleanerName = document.getElementById('cleanerName').value;
    
    if (!kolej || !level || !cleanerName) {
        alert('Please fill in all fields');
        return false;
    }
    
    alert('Cleaner assigned successfully!');
    event.target.reset();
}

// Maintenance Task Assignment Validation
function validateMaintenanceForm(event) {
    event.preventDefault();
    
    const reportId = document.getElementById('reportId').value;
    const maintenanceName = document.getElementById('maintenanceName').value;
    const schedule = document.getElementById('schedule').value;
    const description = document.getElementById('description').value;
    
    if (!reportId || !maintenanceName || !schedule || !description) {
        alert('Please fill in all fields');
        return false;
    }
    
    // Report ID format validation (e.g., R00001)
    const reportIdPattern = /^R\d{5}$/;
    if (!reportIdPattern.test(reportId)) {
        alert('Please enter a valid Report ID format (e.g., R00001)');
        return false;
    }
    
    // Schedule date validation
    const selectedDate = new Date(schedule);
    const today = new Date();
    if (selectedDate < today) {
        alert('Please select a future date for scheduling');
        return false;
    }
    
    alert('Maintenance task assigned successfully!');
    event.target.reset();
}

// Room Checklist Validation
function validateChecklistForm(event) {
    event.preventDefault();
    
    const cleanliness = document.getElementById('cleanliness').value;
    const hygiene = document.getElementById('hygiene').value;
    const comments = document.getElementById('comments').value;
    
    if (!cleanliness || !hygiene) {
        alert('Please select both cleanliness and hygiene status');
        return false;
    }
    
    alert('Checklist submitted successfully!');
    event.target.reset();
}