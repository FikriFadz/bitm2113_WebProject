document.addEventListener("DOMContentLoaded", function() {
    function initializePage() {
        const path = window.location.pathname;
        if (path.includes('dashboardstudent.html')) {
            fetchUserData('dashboard');
        } else if (path.includes('reportstudent.html') || path.includes('checkliststudent.html')
            || path.includes('maintenancestaff.php')  || path.includes('managestaff.php')
            || path.includes('assigntask.php')  || path.includes('datareportstaff.php')
            || path.includes('schedulecleaner.php') || path.includes('taskcleaner.php')
            || path.includes('pending.php')|| path.includes('assignCleaner.php')
            || path.includes('taskhistory.php') ) {
            fetchUserData('report');
        }
    }

    console.log("DOM fully loaded and parsed.");
    initializePage();

    async function fetchUserData(page) {
        try {
            const response = await fetch('userdata.php');
            const data = await response.json();

            if (data.error) {
                document.getElementById('userName').textContent = data.error;
                document.getElementById('userEmail').textContent = "";
                if (page ==='dashboard') {
                    document.getElementById('welcomeMessage').textContent = "Welcome, " + data.error;
                }
                return;
            }

            document.getElementById('userName').textContent = data.name;
            document.getElementById('userEmail').textContent = data.email;
            document.getElementById('studentName').value = data.name;

            if (page === 'dashboard') {
                document.getElementById('welcomeMessage').textContent = `Welcome, ${data.name}`;
            }

        } catch (error) {
            document.getElementById('userName').textContent = "Error fetching user data";
            document.getElementById('userEmail').textContent = error.message;

            if (page === 'dashboard') {
                document.getElementById('welcomeMessage').textContent = "Welcome, Error fetching user data";
            }
        }
    }

    document.getElementById("logoutButton").addEventListener("click", function () {
        window.location.href = "home.html";
    });
});