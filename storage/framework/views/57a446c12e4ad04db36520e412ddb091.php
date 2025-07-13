<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Attendance Report - <?php echo e($classroom->name); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            color: #333;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
            font-size: 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
            color: #333;
        }
        tr:nth-child(even) {
            background-color: #fafafa;
        }
        .status {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 14px;
            font-weight: bold;
        }
        .present {
            background-color: #e6ffe6;
            color: #008000;
        }
        .late {
            background-color: #fff3e6;
            color: #cc7700;
        }
        .excused {
            background-color: #e6f3ff;
            color: #0066cc;
        }
        .absent {
            background-color: #ffe6e6;
            color: #cc0000;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><?php echo e($classroom->name); ?> - Attendance Report</h1>
        <p><?php echo e($date); ?></p>
        <p>Teacher: <?php echo e($classroom->teacher->name); ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Student Name</th>
                <th>Status</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $attendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($index + 1); ?></td>
                <td><?php echo e($attendance->user->name); ?></td>
                <td>
                    <span class="status <?php echo e($attendance->status); ?>">
                        <?php echo e(ucfirst($attendance->status)); ?>

                    </span>
                </td>
                <td><?php echo e($attendance->notes ?? '-'); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <div class="footer">
        <p>Generated on <?php echo e(now()->format('Y-m-d H:i:s')); ?></p>
        <p>Englicious Education Platform</p>
    </div>
</body>
</html> <?php /**PATH D:\Englicious\Englicious\resources\views/attendance/pdf.blade.php ENDPATH**/ ?>