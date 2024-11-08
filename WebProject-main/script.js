
//skripta za prikazivanje/sakrivanje sifre kada editujemo user profil i vrsimo login
function togglePasswordVisibility() 
{
    var passwordInput = document.getElementById("Password");
    var toggleIcon = document.querySelector(".toggle-password i");
  
    if (passwordInput.type === "password") {
      passwordInput.type = "text";
      toggleIcon.classList.remove("fa-eye");
      toggleIcon.classList.add("fa-eye-slash");
    } else 
    {
      passwordInput.type = "password";
      toggleIcon.classList.remove("fa-eye-slash");
      toggleIcon.classList.add("fa-eye");
    }
  }

  // dodatna funkcija zato sto u register imamo password i confirm password
  function toggleConfirmPasswordVisibility() 
{
    var passwordInput = document.getElementById("confirmPassword");
    var toggleIcon = document.querySelector(".toggle-confirmPassword i");
  
    if (passwordInput.type === "password") {
      passwordInput.type = "text";
      toggleIcon.classList.remove("fa-eye");
      toggleIcon.classList.add("fa-eye-slash");
    } else 
    {
      passwordInput.type = "password";
      toggleIcon.classList.remove("fa-eye-slash");
      toggleIcon.classList.add("fa-eye");
    }
  }