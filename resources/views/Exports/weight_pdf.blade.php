@php
    use Morilog\Jalali\Jalalian;
@endphp

<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>راپور انتقالات ترازو ها</title>

    <style>
        body {
            font-family: 'Amiri', serif;
            direction: rtl;
            margin: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th {
            background-color: #1E3752; /* Sky-900 equivalent */
            color: white;
            font-size: 14px;
            padding: 10px;
            border: 1px solid black;
        }

        td {
            font-size: 13px;
            padding: 10px;
            border: 1px solid black;
            text-align: center;
        }

        h2 {
            text-align: center;
            color: #1E3752;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <h2>راپور انتقالات ترازو ها</h2>

    <table>
        <thead>
            <tr>
                <th>آی دی</th>
                <th>ولایت</th>
                <th>نام ترازو</th>
                <th>اسم مشتری / شرکت</th>
                <th>نوع منرال</th>
                <th>منرال انتقال شده</th>
                <th>پول توزین</th>
                <th>تعداد موتر</th>
                <th>شروع انتقالات</th>
                <th>ختم انتقالات</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($weights as $index => $weight)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $weight->province_name }}</td>
                    <td>{{ $weight->scale_name }}</td>
                    <td>{{ $weight->customer_name }}</td>
                    <td>{{ $weight->minral_name }}</td>
                    <td>{{ $weight->mineral_net_weight }} تن</td>
                    <td>{{ $weight->Total_Revenue }} افغانی</td>
                    <td>{{ $weight->cars }}</td>
                    <td>{{ $weight->from_date ? Jalalian::forge($weight->from_date)->format('Y/m/d') : '' }}</td>
                    <td>{{ $weight->to_date ? Jalalian::forge($weight->to_date)->format('Y/m/d') : '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
