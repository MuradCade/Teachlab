<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeachLab | Multi Student Marks Preview</title>
    <link rel="icon" type="image/x-icon" href="https://cdn.pixabay.com/photo/2012/04/24/12/46/letter-39873_640.png">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .progress {
            height: 10px;
        }
        .card {
            border-radius: 1rem;
            overflow: hidden;
            border: none;
        }
        .section-card {
            border-radius: 0.75rem;
            border: 1px solid rgba(0,0,0,.05);
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.035);
            transition: all 0.3s ease;
        }
        .section-card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,.1);
        }
        .table-responsive {
            border-radius: 0.5rem;
            overflow: hidden;
        }
        .badge-present {
            background-color: #d1e7dd;
            color: #0f5132;
        }
        .badge-absent {
            background-color: #f8d7da;
            color: #842029;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10 col-sm-12 col-lg-8">
                <!-- Main Card -->
                <div class="card shadow">
                    <!-- Header -->
                    <div class="card-header bg-primary text-white p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="mb-0"style='font-size:18px;'><i class="bi bi-mortarboard-fill me-2"></i>Student Performance Preview</h2>
                            </div>
                            <div class="text-end">
                                <!-- <h5 class="mb-0">Academic Year 2025</h5> -->
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body p-4">
                        <!-- Student Info -->
                        <div class="row mb-4 align-items-center">
                            <div class="col-md-2 text-center mb-3 mb-md-0">
                                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                    <i class="bi bi-person-fill" style="font-size: 2.5rem;"></i>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <h3 class="mb-1">John Doe</h3>
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge bg-primary">ID: STU2025001</span>
                                    <span class="badge bg-secondary">Grade: 10</span>
                                    <span class="badge bg-info text-dark">Section: A</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Overall Performance Summary -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="section-card p-3 mb-4">
                                    <h4 class="mb-3"><i class="bi bi-bar-chart-fill me-2"></i>Overall Performance</h4>
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <div class="card h-100 bg-light border-0">
                                                <div class="card-body text-center">
                                                    <h6 class="text-muted mb-2">Attendance</h6>
                                                    <h3 class="mb-0">73%</h3>
                                                   <!-- <div class="progress mt-2">
                                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 73%" aria-valuenow="73" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div> -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card h-100 bg-light border-0">
                                                <div class="card-body text-center">
                                                    <h6 class="text-muted mb-2">Assignments</h6>
                                                    <h3 class="mb-0">75%</h3>
                                                   <!-- <div class="progress mt-2">
                                                        <div class="progress-bar bg-info" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div> -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card h-100 bg-light border-0">
                                                <div class="card-body text-center">
                                                    <h6 class="text-muted mb-2">Quizzes</h6>
                                                    <h3 class="mb-0">85%</h3>
                                                    <!-- <div class="progress mt-2">
                                                        <div class="progress-bar bg-success" role="progressbar" style="width: 85%" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div> -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card h-100 bg-light border-0">
                                                <div class="card-body text-center">
                                                    <h6 class="text-muted mb-2">Exams</h6>
                                                    <h3 class="mb-0">80%</h3>
                                                    <!-- <div class="progress mt-2">
                                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Attendance Section -->
                        <div class="section-card p-4 mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="mb-0"><i class="bi bi-calendar-check me-2"></i>Attendance</h4>
                                <span class="badge bg-warning text-dark">73% Present</span>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <div class="card h-100 border-0 bg-light">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h5 class="card-title">Present</h5>
                                                <h5 class="text-success">22</h5>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: 73%" aria-valuenow="73" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <small class="text-muted">22 out of 30 days</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card h-100 border-0 bg-light">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h5 class="card-title">Absent</h5>
                                                <h5 class="text-danger">8</h5>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar bg-danger" role="progressbar" style="width: 27%" aria-valuenow="27" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <small class="text-muted">8 out of 30 days</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h5 class="card-title">Absent Dates</h5>
                                    <div class="d-flex flex-wrap gap-2 mt-2">
                                        <span class="badge badge-absent">2025-03-10</span>
                                        <span class="badge badge-absent">2025-03-12</span>
                                        <span class="badge badge-absent">2025-03-15</span>
                                        <span class="badge badge-absent">2025-03-18</span>
                                        <span class="badge badge-absent">2025-03-22</span>
                                        <span class="badge badge-absent">2025-03-25</span>
                                        <span class="badge badge-absent">2025-03-28</span>
                                        <span class="badge badge-absent">2025-03-30</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Assignments Section -->
                        <div class="section-card p-4 mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="mb-0"><i class="bi bi-journal-text me-2"></i>Assignments</h4>
                                <span class="badge bg-info text-dark">75/100 Points</span>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Assignment</th>
                                            <th>Marks Obtained</th>
                                            <th>Total Marks</th>
                                            <th>Percentage</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Assignment 1</td>
                                            <td>35</td>
                                            <td>50</td>
                                            <td>
                                                <div class="progress">
                                                    <div class="progress-bar bg-warning" role="progressbar" style="width: 70%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100">70%</div>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-warning text-dark">Good</span></td>
                                        </tr>
                                        <tr>
                                            <td>Assignment 2</td>
                                            <td>40</td>
                                            <td>50</td>
                                            <td>
                                                <div class="progress">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100">80%</div>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-success">Excellent</span></td>
                                        </tr>
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <th>Total</th>
                                            <th>75</th>
                                            <th>100</th>
                                            <th>
                                                <div class="progress">
                                                    <div class="progress-bar bg-info" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">75%</div>
                                                </div>
                                            </th>
                                            <th><span class="badge bg-info text-dark">Good</span></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <!-- Quizzes Section -->
                        <div class="section-card p-4 mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="mb-0"><i class="bi bi-question-circle me-2"></i>Quizzes</h4>
                                <span class="badge bg-success">85/100 Points</span>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <div class="card h-100 border-0 bg-light">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h5 class="card-title">Correct Answers</h5>
                                                <h5 class="text-success">17</h5>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: 85%" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <small class="text-muted">17 out of 20 questions</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card h-100 border-0 bg-light">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h5 class="card-title">Incorrect Answers</h5>
                                                <h5 class="text-danger">3</h5>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar bg-danger" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <small class="text-muted">3 out of 20 questions</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Quiz</th>
                                            <th>Marks Obtained</th>
                                            <th>Total Marks</th>
                                            <th>Correct</th>
                                            <th>Incorrect</th>
                                            <th>Performance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Quiz 1</td>
                                            <td>45</td>
                                            <td>50</td>
                                            <td><span class="badge bg-success">9</span></td>
                                            <td><span class="badge bg-danger">1</span></td>
                                            <td>
                                                <div class="progress">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width: 90%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100">90%</div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Quiz 2</td>
                                            <td>40</td>
                                            <td>50</td>
                                            <td><span class="badge bg-success">8</span></td>
                                            <td><span class="badge bg-danger">2</span></td>
                                            <td>
                                                <div class="progress">
                                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100">80%</div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <th>Total</th>
                                            <th>85</th>
                                            <th>100</th>
                                            <th><span class="badge bg-success">17</span></th>
                                            <th><span class="badge bg-danger">3</span></th>
                                            <th>
                                                <div class="progress">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width: 85%" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100">85%</div>
                                                </div>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <!-- Exams Section -->
                        <div class="section-card p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="mb-0"><i class="bi bi-file-earmark-text me-2"></i>Exams</h4>
                                <span class="badge bg-primary">120/150 Points</span>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <div class="card h-100 border-0 bg-light">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h5 class="card-title">Correct Answers</h5>
                                                <h5 class="text-success">25</h5>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: 83%" aria-valuenow="83" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <small class="text-muted">25 out of 30 questions</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card h-100 border-0 bg-light">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h5 class="card-title">Incorrect Answers</h5>
                                                <h5 class="text-danger">5</h5>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar bg-danger" role="progressbar" style="width: 17%" aria-valuenow="17" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <small class="text-muted">5 out of 30 questions</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Exam</th>
                                            <th>Marks Obtained</th>
                                            <th>Total Marks</th>
                                            <th>Correct</th>
                                            <th>Incorrect</th>
                                            <th>Performance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Exam 1</td>
                                            <td>60</td>
                                            <td>75</td>
                                            <td><span class="badge bg-success">15</span></td>
                                            <td><span class="badge bg-danger">5</span></td>
                                            <td>
                                                <div class="progress">
                                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100">80%</div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Exam 2</td>
                                            <td>60</td>
                                            <td>75</td>
                                            <td><span class="badge bg-success">10</span></td>
                                            <td><span class="badge bg-danger">5</span></td>
                                            <td>
                                                <div class="progress">
                                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100">80%</div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <th>Total</th>
                                            <th>120</th>
                                            <th>150</th>
                                            <th><span class="badge bg-success">25</span></th>
                                            <th><span class="badge bg-danger">5</span></th>
                                            <th>
                                                <div class="progress">
                                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100">80%</div>
                                                </div>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Footer -->
                    <div class="card-footer bg-light p-4">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                            <div class="mb-3 mb-md-0">
                                <p class="text-muted mb-0">Last updated: April 5, 2025</p>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-primary">
                                    <i class="bi bi-printer me-2"></i>Print Report
                                </button>
                                <button class="btn btn-primary">
                                    <i class="bi bi-download me-2"></i>Download Report
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>