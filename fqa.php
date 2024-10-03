<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - MME Micro Credit Employee Management System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
            color: #333;
            background-image: linear-gradient(rgba(0, 0, 0, 0.382),rgba(0, 0, 0, 0.974)),url(WhatsApp\ Image\ 2024-09-26\ at\ 13.09.52_c57ebbf2.jpg);
   background-repeat: no-repeat;
   background-position: center;
   background-size: 200%;
        }

        .container {
            max-width: 700px;
            margin: 20px auto;
            padding: 10px;
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            font-size: 1.8rem;
            font-weight: 600;
            color: white;
            margin-bottom: 20px;
            font-family:cursive;
        }

        .card {
            border: none;
            margin-bottom: 10px;
            transition: transform 0.2s ease;
        }

        .card-header {
            background-color: #007bff;
            color: white;
            padding: 10px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            border-radius: 5px;
        }

        .card-body {
            background-color: #fff;
            padding: 10px;
            font-size: 0.9rem;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.85rem;
            color: #777;
        }

        /* Compacting the design */
        .card-header, .card-body {
            margin: 0;
        }

        h1 {
            font-size: 1.5rem;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>FAQs</h1>

        <!-- FAQ Cards with Bootstrap Collapse -->
        <div class="accordion" id="faqAccordion">
            <div class="card">
                <div class="card-header" id="headingOne">
                    <h5 class="mb-0">
                        <button class="btn btn-link text-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            What is the purpose of this Employee Management System?
                        </button>
                    </h5>
                </div>

                <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                    <div class="card-body">
                        This system helps manage employee information, attendance, leave requests, and payroll for MME Micro Credit.
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header" id="headingTwo">
                    <h5 class="mb-0">
                        <button class="btn btn-link text-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            How do I add a new employee?
                        </button>
                    </h5>
                </div>

                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                    <div class="card-body">
                        Go to the "Add Employee" page, fill in the details, and submit.
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header" id="headingThree">
                    <h5 class="mb-0">
                        <button class="btn btn-link text-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            How do I track employee attendance?
                        </button>
                    </h5>
                </div>

                <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                    <div class="card-body">
                        You can view attendance records from the "Attendance" page.
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header" id="headingFour">
                    <h5 class="mb-0">
                        <button class="btn btn-link text-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            How do employees submit leave requests?
                        </button>
                    </h5>
                </div>

                <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                    <div class="card-body">
                        Employees can submit leave requests via the leave form, and admins can approve or reject them.
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header" id="headingFive">
                    <h5 class="mb-0">
                        <button class="btn btn-link text-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                            How does payroll management work?
                        </button>
                    </h5>
                </div>

                <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-bs-parent="#faqAccordion">
                    <div class="card-body">
                        Admins can manage payroll records via the "Payroll" page.
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header" id="headingSix">
                    <h5 class="mb-0">
                        <button class="btn btn-link text-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                            How do SMS notifications work?
                        </button>
                    </h5>
                </div>

                <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-bs-parent="#faqAccordion">
                    <div class="card-body">
                        The system uses Africa's Talking SMS API to notify employees about leave approvals or rejections.
                    </div>
                </div>
            </div>
        </div>

    </div>

    <footer>
        <p>&copy; 2024 MME Micro Credit - All Rights Reserved</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
