<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الشروط والأحكام</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9fafb;
            color: #333;
        }

        .dark body {
            background-color: #1a202c;
            color: #cbd5e0;
        }
    </style>
</head>

<body class="bg-gray-100 dark:bg-gray-800">

    <!-- Header -->
    <header class="bg-gray-900 w-full shadow-md z-10 text-white">
        <nav class="container mx-auto flex justify-between items-center p-4">
            <h1 class="text-2xl font-bold">الشروط والأحكام</h1>
            <button id="dark-mode-toggle" class="ml-3 px-3 py-1 rounded bg-gray-800 hover:bg-gray-700 transition">
                تبديل الوضع الليلي
            </button>
        </nav>
    </header>

    <!-- Terms Section -->
    <section class="container mx-auto mt-8 p-6 bg-white shadow-md rounded-lg">
        <h2 class="text-2xl font-bold mb-4">الشروط والأحكام</h2>
        <p class="text-gray-700 mb-4">
            مرحبًا بك في منصتنا. باستخدامك لهذه المنصة، فإنك توافق على الالتزام بالشروط والأحكام التالية:
        </p>
        <ul class="list-disc pl-6 text-gray-700 space-y-2">
            <li>يجب استخدام المنصة للأغراض التعليمية فقط.</li>
            <li>يحظر نشر أو تحميل أي محتوى غير قانوني أو ينتهك حقوق الآخرين.</li>
            <li>المعلومات المتاحة عبر المنصة مملوكة لجامعة الزقازيق، ولا يجوز نسخها دون إذن مسبق.</li>
            <li>تحتفظ الجامعة بحق تعديل هذه الشروط في أي وقت دون إشعار مسبق.</li>
            <li>في حالة مخالفة أي من الشروط، يحق لنا اتخاذ الإجراءات القانونية المناسبة.</li>
        </ul>

        <h3 class="text-xl font-bold mt-6 mb-2">حقوق الملكية الفكرية</h3>
        <p class="text-gray-700">
            جميع الحقوق محفوظة لجامعة الزقازيق. يُمنع إعادة استخدام أو توزيع أي محتوى من المنصة دون إذن مسبق.
        </p>

        <h3 class="text-xl font-bold mt-6 mb-2">سياسة الخصوصية</h3>
        <p class="text-gray-700">
            نحن ملتزمون بحماية خصوصيتك. تُستخدم بياناتك الشخصية فقط للأغراض الأكاديمية ولن يتم مشاركتها مع أي طرف ثالث دون إذن.
        </p>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-8 text-center mt-16">
        <p>&copy; 2024 جامعة الزقازيق. جميع الحقوق محفوظة.</p>
    </footer>

    <!-- JavaScript -->
    <script>
        // Toggle Dark Mode
        document.getElementById('dark-mode-toggle').addEventListener('click', () => {
            document.body.classList.toggle('dark');
        });
    </script>

</body>

</html>
