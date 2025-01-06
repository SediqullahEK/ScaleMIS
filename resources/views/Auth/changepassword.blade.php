<x-layout>

    <div dir="rtl">
        <div class="my-2">
            <h1 class="font-bold border border-gray-200 shadow p-2 rounded">تبدیل نمودن رمز</h1>
        </div>
        @if(session('success'))
            <div id="message" class="py-4">
                <p  class="p-2 w-[500px] mx-auto bg-green-400 text-gray-100 rounded-md text-center shadow-lg">{{ session('success') }}</p>
            </div>
        @endif
        <div class="border border-gray-200 shadow rounded-md bg-white">
            <form class="max-w-3xl p-2 mx-auto" action="{{ route('updatepassword') }}" method="POST">
                @csrf
                <x-input type="password" name="password" title="رمز جدید" placeholder="رمز جدید را وادر کنید" />
                <x-input type="password" name="password_confirmation" title="تاییدی رمز جدید" placeholder="رمز جدید را دوباره وارد کنید" />
                <div class="flex justify-end">
                    <x-button-simple title="ذخیره" type="submit" />
                </div>
                
            </form>
        </div>
    </div>
</x-layout>


<script>

    setTimeout(() => {
        document.getElementById('message').style.display = 'none';
    }, 4000);

</script>