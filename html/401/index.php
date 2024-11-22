<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = {
    content: [
        "./html/**/*",
    ],
    theme: {
        extend: {
            fontFamily: {
                'cormorant': ['Cormorant-Bold'],
                'sans': ['Poppins'],
            },
            fontSize: {
                'small': ['14px'],
                'h1': ['32px'],
                'h2': ['24px'],
                'h3': ['20px'],
                'h4': ['18px'],
                'PACT': ['35px', {
                    letterSpacing: '0.2em',
                }],
            },
            colors: {
                'rouge-logo': '#EA4335',
                'primary': '#F2771B',
                'secondary': '#0a0035',
                'base100': '#F1F3F4',
                'base200': '#E0E0E0',
                'base300': '#CCCCCC',
                'neutre': '#000',
                'gris': '#828282',
                'bgBlur': "#F1F3F4",
                'veryGris': "#BFBFBF",
            },
            spacing: {
                '1/6': '16%',
            },
            animation: {
                'expand-width': 'expandWidth 1s ease-out forwards',
            },
            keyframes: {
                expandWidth: {
                    '0%': { width: '100%' },
                    '100%': { width: '0%' },
                },
            },
            boxShadow: {
                'custom': '0 0 12px 12px rgba(210, 210, 210, 0.5)',
            }
        },
    },
    plugins: [],
}
</script>
    <link rel="icon" type="image" href="/public/images/favicon.png">
    <title>Accès refusé</title>
    <script type="module" src="/scripts/loadComponents.js" defer=""></script>
    <script type="module" src="/scripts/main.js" defer=""></script>
</head>

<body class="min-h-screen flex flex-col">
    <div id="header"></div>
    <main class="md:w-full mt-0 m-auto flex max-w-[1280px] p-2">
        <div id="menu" class="absolute md:block"></div>
        <div class="m-auto text-center">
            <h1 class="font-cormorant text-[10rem]">401</h1>
            <p>Vous ne pouvez pas accéder à cette page.</p>
            <img src="https://i.pinimg.com/originals/e0/5a/70/e05a70b23f36987ff395063a1e193db7.gif" class="mt-10 rounded-lg m-auto" alt="tottereau" width="250">
        </div>
    </main>
    <div id="footer" class=""></div>
</body>

</html>