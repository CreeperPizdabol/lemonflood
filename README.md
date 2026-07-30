<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lemon Flood</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="favicon.png">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Press+Start+2P&family=Inter:wght@400;500;600;700&display=swap');

        body { font-family: 'Inter', sans-serif; }
        .logo-font { font-family: 'Press Start 2P', sans-serif; }

        .hero {
            background: linear-gradient(135deg, rgba(255,235,59,0.2), rgba(134,239,172,0.25)),
                        url('https://i.postimg.cc/8CgHsnWY/IMG-20260419-024123-828.jpg') center/cover no-repeat;
            min-height: 100vh; 
        }

        .character {
            animation: float 7s ease-in-out infinite;
            filter: drop-shadow(0 25px 25px rgba(255,235,59,0.4));
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(-5deg); }
            50% { transform: translateY(-25px) rotate(5deg); }
        }

        .glow { text-shadow: 0 0 30px #ffeb3b, 0 0 60px #86efac; }
        
        .rule-card {
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(12px); 
            border: 2px solid rgba(255,235,59,0.5);
        }
    </style>
</head>
<body class="bg-neutral-950 text-white">

    <!-- HEADER -->
    <header class="bg-black/90 backdrop-blur-md border-b border-yellow-300 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-5 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <img src="https://i.postimg.cc/TPv9hHmS/IMG-20260419-024433-267.jpg" 
                     class="w-11 h-11 object-cover rounded-2xl border-2 border-yellow-300" alt="Lemon">
                <h1 class="logo-font text-3xl tracking-widest text-yellow-300 glow">LEMON FLOOD</h1>
            </div>
            
            <!-- Десктоп меню -->
            <nav class="hidden md:flex gap-8 text-lg items-center">
                <a href="#home" class="hover:text-yellow-300">Главная</a>
                <a href="#server" class="hover:text-yellow-300">Сервер</a>
                <a href="#rules" class="hover:text-yellow-300">Правила</a>
                <a href="#about" class="hover:text-yellow-300">О нас</a>
                <a href="#contacts" class="hover:text-yellow-300">Контакты</a>
                <button type="button" class="open-application px-5 py-2 bg-gradient-to-r from-yellow-300 to-lime-300 text-black rounded-2xl font-bold">Подать заявку</button>
            </nav>

            <!-- Мобильное меню -->
            <button id="menu-btn" class="md:hidden text-3xl text-yellow-300">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>

        <!-- Мобильное меню -->
        <div id="mobile-menu" class="hidden md:hidden bg-black/95 border-t border-yellow-300 py-6">
            <div class="flex flex-col gap-6 text-center text-xl">
                <a href="#home" class="hover:text-yellow-300 py-2">Главная</a>
                <a href="#server" class="hover:text-yellow-300 py-2">Сервер</a>
                <a href="#rules" class="hover:text-yellow-300 py-2">Правила</a>
                <a href="#about" class="hover:text-yellow-300 py-2">О нас</a>
                <a href="#contacts" class="hover:text-yellow-300 py-2">Контакты</a>
                <button type="button" class="open-application mx-auto px-8 py-4 bg-gradient-to-r from-yellow-300 to-lime-300 text-black rounded-2xl font-bold">Подать заявку</button>
            </div>
        </div>
    </header>

    <!-- HERO -->
    <section id="home" class="hero flex items-center relative">
        <div class="max-w-7xl mx-auto px-5 py-20 md:py-0 grid md:grid-cols-2 gap-12 items-center">
            <div class="space-y-6 text-center md:text-left">
                <h1 class="logo-font text-6xl md:text-8xl font-black leading-none text-yellow-300 glow">
                    LEMON<br>FLOOD
                </h1>
                <p class="text-xl logo-font text-yellow-300 md:text-3xl text-white/90">
                    Lemon Flood - это флуд чудес и удовольствий ,
		    где ты можешь всегда найти друзей поместится в уютную атмосферу.<br>
                </p>
                <button type="button" class="open-application px-8 py-5 bg-gradient-to-r from-yellow-300 to-lime-300 text-black rounded-2xl font-bold text-xl">Подать заявку</button>
            </div>

            <!-- Картинка -->
            <div class="flex justify-center md:justify-end">
                <img src="https://i.postimg.cc/TPv9hHmS/IMG-20260419-024433-267.jpg" 
                     alt="Lemon Flood" 
                     class="character w-80 md:w-[420px] rounded-3xl border-8 border-yellow-300 shadow-2xl">
            </div>
        </div>
    </section>

    <!-- SERVER -->
    <section id="server" class="py-20 bg-neutral-900">
        <div class="max-w-7xl mx-auto px-5">
            <h1 class="logo-font text-3xl tracking-widest text-yellow-300 glow">LEMON SERVER</h1>
            <div class="grid md:grid-cols-2 gap-10">
                <div class="text-center md:text-left">
                    <p class="text-2xl leading-relaxed">
                        Полуванильный Minecraft сервер<br>
                        Без доната • Без привата<br>
                        <span class="text-lime-300">Играем на доверии</span>
                    </p>
                </div>
                <div class="grid grid-cols-2 gap-4 text-lg">
                    <div class="bg-neutral-800 p-6 rounded-3xl text-center">👀Без доната и привилегий которые дают преимущество</div>
                    <div class="bg-neutral-800 p-6 rounded-3xl text-center">🤝Нет привата , играем на доверии игроков</div>
                    <div class="bg-neutral-800 p-6 rounded-3xl text-center">😉Кастомная генерация</div>
                    <div class="bg-neutral-800 p-6 rounded-3xl text-center">❤️Уют и удовольствие</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ПРАВИЛА -->
    <section id="rules" class="py-20 bg-black">
        <div class="max-w-6xl mx-auto px-5">
            <h2 class="text-5xl font-bold text-yellow-300 text-center mb-16 glow">ПРАВИЛА НАШЕГО ФЛУДА И СЕРВЕРА</h2>
            
            <div class="grid md:grid-cols-2 gap-10">
                <div>
                    <h3 class="text-3xl text-lime-300 mb-6 text-center md:text-left">Правила чата</h3>
                    <div class="space-y-4">
                        <div class="rule-card p-6 rounded-3xl">1 | 18+ контент строго запрещен. Наказание: варн + мут на 1 час . При повторном нарушении мут увеличивается на 5 часов (варн так же дается) Причина: многим дизкомфортно и мерзко видеть в чате фото/видео/гифки сексуального характера</div>
                        <div class="rule-card p-6 rounded-3xl">2 | Рейд флуда или любая атака на флуд Наказание: бан без возможности возврата назад Причина:думаю тут и так ясно</div>
                        <div class="rule-card p-6 rounded-3xl">3 | Спам стикерами/гифкамм и сообщениями которые не несут смысла Если вы пересылаете сообщение то это не считается за спам. (Спам считается после 3 сообщений) Наказание: варн . После повторного нарушения дается мут и варн на 2 часа Причина: Флуд создан для общения.  И спам сообщениями мешает общению и засоряет чат </div>
						<div class="rule-card p-6 rounded-3xl">4 | Ссора между участниками.  Если завязывается ссора то просьба идти в личные сообщения Наказание: Варн начавшему ссору. Если человек продолжит ссору то дается мут на час Причина: Ссора доставляет дизкомфорт участниками. И флуду не будет приятно наблюдать за ссорами. Так что поэтому если понимаете что ссору не избежать идите в личные сообщения </div>
						<div class="rule-card p-6 rounded-3xl">5 | Упоминания сво, шутки про войну и т.п Наказание: варн Причина:Для некоторых участников эта тема может быть больной и травмой.</div>
						<div class="rule-card p-6 rounded-3xl">6 | Шутки над участником за внешность, голос, картавость и т.п (Если человек против этого)Наказание : мут час и варн. Мут выдается на 2 часа при повторном нарушении </div>
						<div class="rule-card p-6 rounded-3xl">7 | Шутки/Оскорбления про родителей/национальность/религию/ориентацию Наказание: варн + мут 4 часа Причина: Еще раз повторю мы не выбирали кем родиться и кем нам быть.  Человеку может быть не приятно от этого и даже обидно. </div>
						<div class="rule-card p-6 rounded-3xl">8 | Закрепление не нужной информации (если владелец разрешил закрепить временно то это не считается) Наказание: варн / понижение (если вы админ) Причина: Закрепленные сообщения должны быть для того чтобы участники могли найти важную информацию в чате</div>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-3xl text-lime-300 mb-6 text-center md:text-left">Правила сервера</h3>
                    <div class="space-y-4">
                        <div class="rule-card p-6 rounded-3xl">1 | Использование читов и подусторонних программ</div>
                        <div class="rule-card p-6 rounded-3xl">2 | Если вы хотите использовать саундпад , то сначало спросите игроков хотят они этого или нет</div>
                        <div class="rule-card p-6 rounded-3xl">3 | Оскорбления, токсичность и провокации голосом запрещены.</div>
						<div class="rule-card p-6 rounded-3xl">4 | Администрация может отключить доступ к голосовому чату при нарушениях.</div>
						<div class="rule-card p-6 rounded-3xl">5 | Не стройте вплотную к спавну и чужим постройкам без согласия.</div>
						<div class="rule-card p-6 rounded-3xl">6 | Запрещены багоюзы и баги. Если вы нашли баг или багоюз пишите @MykaPsql</div>
						<div class="rule-card p-6 rounded-3xl">7 | Гриф/Таргет/пвп запрещен.</div>
						<div class="rule-card p-6 rounded-3xl">8 | Лаг машины и дюп запрещены</div>
						<div class="rule-card p-6 rounded-3xl">9 | Хранение админ вещей (сильных) запрещено, в случае находке вы можете избавится от них или будет наказание</div>
                    </div>
                </div>
    </section>

    <!-- О НАС -->
    <section id="about" class="py-20 bg-neutral-900 text-center">
        <div class="max-w-4xl mx-auto px-5">
            <h2 class="text-5xl font-bold text-yellow-300 mb-8">О НАС</h2>
            <p class="text-2xl text-white/80">Уютное сообщество, где каждый может почувствовать себя как дома.</p>
            <p class="text-2xl text-white/80">Хороший владелец , тех. админ , которые всегда могут расмешить:) </p>
            <div class="mt-12 text-6xl font-black text-lime-300">46 участников во флуде</div>
            <div class="mt-12 text-6xl font-black text-lime-300">Заходи тебя тоже ждут:3</div>
        </div>
    </section>

    <!-- КОНТАКТЫ -->
    <section id="contacts" class="py-20 bg-black text-center">
        <div class="max-w-7xl mx-auto px-5">
            <h2 class="text-5xl font-bold text-yellow-300 mb-12">Контакты</h2>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="https://t.me/KrisDreemurrMyBeloved" target="_blank" class="px-8 py-5 bg-gradient-to-r from-yellow-300 to-lime-300 text-black rounded-2xl font-bold">Крис (Владелец)</a>
                <a href="https://t.me/MykaPsql" target="_blank" class="px-8 py-5 bg-gradient-to-r from-yellow-300 to-lime-300 text-black rounded-2xl font-bold">Набстаблук (Тех. Админ)</a>
            </div>
        </div>
            <a href="https://www.tiktok.com/@tennaflood" target="_blank" class="mt-12 inline-flex items-center gap-1 text-2xl hover:text-yellow-300">
                <i class="fa-brands fa-tiktok"></i> TikTok
            </a>
			
			<a href="https://t.me/LemonFloodInfo" target="_blank" class="mt-12 inline-flex items-center gap-1 text-2xl hover:text-yellow-300">
                <i class="fa-brands fa-telegram"></i> Telegarm
            </a>
        </div>
    </section>

    <footer class="py-8 text-center text-sm text-yellow-300/60 border-t border-yellow-300/20">
        Lemon Flood © 2025-2026 • Сделано с душой 🍋
    </footer>

    <div id="application-modal" class="hidden fixed inset-0 z-[60] bg-black/80 backdrop-blur-sm px-5 py-8">
        <div class="min-h-full flex items-center justify-center">
            <div class="rule-card w-full max-w-lg p-6 rounded-3xl relative">
                <button type="button" id="close-application" class="absolute top-4 right-4 text-3xl text-yellow-300 hover:text-lime-300" aria-label="Закрыть">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                <h2 class="text-3xl font-bold text-yellow-300 mb-8 text-center glow">Подать заявку</h2>
                <form id="application-form" class="space-y-5">
                    <div>
                        <label for="telegram-username" class="block mb-2 text-lg text-yellow-300">Ваш тг юз:</label>
                        <input id="telegram-username" name="telegramUsername" type="text" required minlength="2" placeholder="@username" class="w-full px-5 py-4 bg-neutral-950/80 border-2 border-yellow-300/60 rounded-2xl text-white outline-none focus:border-lime-300">
                    </div>
                    <div>
                        <label for="role" class="block mb-2 text-lg text-yellow-300">Какую роль вы занимаете:</label>
                        <input id="role" name="role" type="text" required minlength="2" placeholder="Например: Крис, Набстаблук" class="w-full px-5 py-4 bg-neutral-950/80 border-2 border-yellow-300/60 rounded-2xl text-white outline-none focus:border-lime-300">
                    </div>
                    <button type="submit" class="w-full px-8 py-5 bg-gradient-to-r from-yellow-300 to-lime-300 text-black rounded-2xl font-bold text-xl">Отправить</button>
                    <p id="application-status" class="min-h-6 text-center text-lime-300"></p>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Мобильное меню
        const menuBtn = document.getElementById('menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        
        menuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
            const icon = menuBtn.querySelector('i');
            icon.classList.toggle('fa-bars');
            icon.classList.toggle('fa-xmark');
        });

        // Плавный скролл
        document.querySelectorAll('a[href^="#"]').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                document.querySelector(link.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
                if (!mobileMenu.classList.contains('hidden')) {
                    mobileMenu.classList.add('hidden');
                }
            });
        });

        const applicationModal = document.getElementById('application-modal');
        const applicationForm = document.getElementById('application-form');
        const applicationStatus = document.getElementById('application-status');
        const closeApplication = document.getElementById('close-application');

        document.querySelectorAll('.open-application').forEach(button => {
            button.addEventListener('click', () => {
                applicationModal.classList.remove('hidden');
                applicationStatus.textContent = '';
                if (!mobileMenu.classList.contains('hidden')) {
                    mobileMenu.classList.add('hidden');
                }
            });
        });

        closeApplication.addEventListener('click', () => {
            applicationModal.classList.add('hidden');
        });

        applicationModal.addEventListener('click', (event) => {
            if (event.target === applicationModal) {
                applicationModal.classList.add('hidden');
            }
        });

        applicationForm.addEventListener('submit', async (event) => {
            event.preventDefault();
            applicationStatus.textContent = 'Отправляем...';

            const formData = new FormData(applicationForm);
            const payload = {
                telegramUsername: formData.get('telegramUsername').trim(),
                role: formData.get('role').trim()
            };

            try {
                const response = await fetch('send.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const contentType = response.headers.get('content-type') || '';
                if (!contentType.includes('application/json')) {
                    throw new Error('Сервер вернул неверный ответ. Проверьте, что сайт открыт через хостинг с PHP');
                }

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.error || 'Не получилось отправить заявку');
                }

                applicationForm.reset();
                applicationStatus.textContent = 'Заявка отправлена!';
            } catch (error) {
                applicationStatus.textContent = error.message;
            }
        });
    </script>
</body>
</html>
