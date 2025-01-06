@php
    use Morilog\Jalali\Jalalian;
    use Carbon\Carbon;
@endphp
<table 
    class="w-full text-sm text-center text-gray-500 dark:text-gray-400 border-separate border-spacing-0 border border-slate-100">
    <thead class="text-xs text-gray-50 bg-sky-900 uppercase dark:bg-gray-700 dark:text-gray-400">
        <tr>
            <th>آی دی</th>
            <th>ولایت</th>
            <th>نام ترازو</th>
            <th>اسم مشتری / شرکت</th>
            <th>نوع منرال</th>
            <th>وزن خالص</th>
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
                <td>{{ $weight->mineral_net_weight }}</td>
                <td>{{ $weight->Total_Revenue }}</td>
                <td>{{ $weight->cars }}</td>
                <td>{{ Jalalian::forge($weight->from_date)->format('Y/m/d') }}</td>
                <td>{{ Jalalian::forge($weight->to_date)->format('Y/m/d') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
