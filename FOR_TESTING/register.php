<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="row border rounded-4 p-3 bg-white shadow box-area">
            <div class="col-md-5 rounded-4 d-flex justify-content-center align-items-center flex-column left-box" style="background: #030067">
                <div class="featured-image mb-3">
                    <img src="../images/icct_logo.png" class="img-fluid" />
                </div>
            </div>
            <div class="col-md-7 right-box">
                <div class="row align-items-center">
                    <div class="header-text mb-4">
                        <h2>Student Register</h2>
                        <p>To register ICCT Portal, please make sure you meet the following requirements:</p>
                    </div>
                    <form id="myForm" action="" method="post" novalidate>
                        <label class="form-label">First Name:</label>
                        <div class="input-group mb-2">
                            <input type="text" name="firstName" class="form-control bg-light fs-6" placeholder="Juan" required />
                            <div class="invalid-feedback">Please enter your first name.</div>
                        </div>
                        <label class="form-label">Middle Name:</label>
                        <div class="input-group mb-2">
                            <input type="text" name="middleName" class="form-control bg-light fs-6" placeholder="" />
                        </div>
                        <label class="form-label">Last Name:</label>
                        <div class="input-group mb-2">
                            <input type="text" name="lastName" class="form-control bg-light fs-6" placeholder="Dela Cruz" required />
                            <div class="invalid-feedback">Please enter your last name.</div>
                        </div>
                        <label class="form-label">Suffix:</label>
                        <div class="input-group mb-2">
                            <select name="suffix" id="suffix" class="form-select bg-light fs-6">
                                <option value="">Select suffix</option>
                                <option value="jr">Jr</option>
                                <option value="sr">Sr</option>
                                <option value="II">II</option>
                                <option value="III">III</option>
                                <option value="IV">IV</option>
                            </select>
                        </div>
                        <label class="form-label">Course:</label>
                        <div class="input-group mb-2">
                            <select name="course" id="course" class="form-select bg-light fs-6" required>
                                <option value="">Select course</option>
                                <option value="BSIT">BSIT - Bachelor of Science in Information Technology</option>
                                <option value="BSCS">BSCS - Bachelor of Science in Computer Science</option>
                                <option value="BSCE">BSCE - Bachelor of Science in Computer Engineering</option>
                                <option value="BSIS">BSIS - Bachelor of Science in Information Science</option>
                                <option value="ABCom">ABCom - Bachelor of Arts in Communication (Masscom)</option>
                                <option value="ABEng">ABEng - Bachelor of Arts in English</option>
                                <option value="ABPolSci">ABPolSci - Bachelor of Arts in Political Science</option>
                                <option value="ABPsych">ABPsych - Bachelor of Arts in Psychology</option>
                                <option value="BSM">BSM - Bachelor of Sciences in Mathematics</option>
                            </select>
                        </div>
                        <label class="form-label">Campus:</label>
                        <div class="input-group mb-2">
                            <select name="campus" id="campus" class="form-select bg-light fs-6" required>
                                <option value="">Select campus</option>
                                <option value="Cainta">Cainta (Main)</option>
                                <option value="Cubao">Cubao</option>
                                <option value="San Mateo">San Mateo</option>
                                <option value="Antipolo">Antipolo</option>
                                <option value="Binangonan">Binangonan</option>
                            </select>
                        </div>  
                        <label class="form-label">Contact Number:</label>
                        <div class="input-group mb-2">
                            <input type="text" class="form-control bg-light fs-6" name="contactNum" id="phone" pattern="[0-9]{11}" placeholder="Enter Phone Number" />
                            <div class="invalid-feedback">Please enter a valid 11-digit phone number.</div>
                        </div>
                        <label class="form-label">Email address:</label>
                        <div class="input-group mb-2">
                            <input type="email" name="email" class="form-control bg-light fs-6" placeholder="example@example.com" required />
                        </div>
                        <label class="form-label">Password:</label>
                        <div class="input-group mb-2">
                            <input type="password" name="password" class="form-control bg-light fs-6" placeholder="********" required />
                        </div>
                        <label class="form-label">Confirm Password:</label>
                        <div class="input-group mb-3">
                            <input type="password" name="confirmPassword" class="form-control bg-light fs-6" placeholder="********" required />
                        </div>
                        <div class="input-group mb-2">
                            <button type="submit" name="submit" class="btn btn-lg w-100 fs-6" style="background-color: #030067; color: #ececec;">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        'use strict'
        var form = document.getElementById("myForm");

        form.addEventListener("submit", function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }

            let phoneInput = document.getElementById("phone");
            let phoneRegex = /^[0-9]{11}$/;
            if (!phoneRegex.test(phoneInput.value)) {
                phoneInput.setCustomValidity("Please enter a valid 11-digit phone number.");
            } else {
                phoneInput.setCustomValidity("");
            }

            form.classList.add('was-validated');
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
