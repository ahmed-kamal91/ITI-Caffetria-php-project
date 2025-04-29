<?php
// header.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            transition: all 0.3s;
        }
        .main-content {
            flex: 1;
            padding: 20px;
            transition: all 0.3s;
        }
        .status-available {
            color: #28a745;
        }
        .status-unavailable {
            color: #dc3545;
        }
        .drink-card {
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.2s;
            height: 100%;
        }
        .drink-card:hover {
            transform: translateY(-5px);
        }
        .drink-image-container {
            height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
            overflow: hidden;
        }
        .drink-image {
            max-height: 100%;
            max-width: 100%;
            object-fit: contain;
        }
        .card-footer {
            background-color: white;
        }
        .availability-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 0.8rem;
        }
        .pagination {
            margin-top: 20px;
            justify-content: center;
        }
        .page-item.active .page-link {
            background-color: #e9ecef;
            border-color: #dee2e6;
            color: #495057;
        }
        .page-link {
            color: #495057;
        }
        .row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -15px;
            margin-left: -15px;
        }
        .col-md-4 {
            padding-right: 15px;
            padding-left: 15px;
        }
        .alert {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <?php include 'sidebar.php'; ?>
        <div class="main-content" id="mainContent">