let menuicn = document.getElementById("menuicn");
let nav = document.querySelector(".navcontainer");

menuicn.addEventListener("click", () => {
    nav.classList.toggle("navclose");
});

    
function logout() {
    console.log("Logout button clicked"); // Check if the function is being called
    // Clear the session
    sessionStorage.clear(); // Clear session storage
    localStorage.clear();   // Clear local storage

    // Redirect to the login page
    window.location.href = "admin_login.aspx";
}

function confirmLogout() {
    if (confirm("Are you sure you want to logout?")) {
        // If user confirms logout, redirect to the logout page
        window.location.href = "admin_logout.aspx";
    }
}

