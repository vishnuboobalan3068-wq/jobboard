{{-- FAQ help bot. Ready-made answers matched by keywords, not real AI, so it needs no API key. --}}
@php
    // Show a link only if the viewer's role can open that page. Guests are sent to login first.
    $linkFor = fn (string $role, string $text, string $url) => (! auth()->check() || auth()->user()->role === $role)
        ? ['text' => $text, 'url' => $url]
        : null;

    $faqs = [
        [
            'q' => 'How do I apply for a job?',
            'keywords' => ['apply', 'apply panna', 'submit application'],
            'a' => 'Log in as a job seeker, open a job and fill in the "Apply for this job" box. Upload your resume, add a cover letter if you like, then click Submit application.',
            'link' => ['text' => 'Browse jobs', 'url' => route('jobs.index')],
        ],
        [
            'q' => 'How do I upload my resume?',
            'keywords' => ['resume', 'cv', 'upload', 'pdf'],
            'a' => 'You can upload a PDF, DOC or DOCX of up to 2 MB when you apply. Job seekers can also save one under Profile, so they can apply without uploading it again.',
            'link' => $linkFor('seeker', 'Open my profile', route('profile.edit')),
        ],
        [
            'q' => 'How do I search for jobs?',
            'keywords' => ['search', 'find', 'filter', 'browse', 'location', 'category', 'thedu'],
            'a' => 'Open Browse Jobs and filter by keyword, location, category or job type. You can do this without an account.',
            'link' => ['text' => 'Browse jobs', 'url' => route('jobs.index')],
        ],
        [
            'q' => 'What are job alerts?',
            'keywords' => ['alert', 'notify', 'notification', 'bell'],
            'a' => 'A job seeker can save an alert with a keyword, location, category or job type. When a matching job is posted, a notification appears under the bell icon.',
            'link' => $linkFor('seeker', 'Open job alerts', route('seeker.alerts.index')),
        ],
        [
            'q' => 'How do I track my application?',
            'keywords' => ['track', 'status', 'shortlist', 'rejected', 'hired', 'my application'],
            'a' => 'Job seekers can see each status under My Applications: Pending, Shortlisted, Rejected or Hired. You also get a notification when the employer changes it.',
            'link' => $linkFor('seeker', 'Open my applications', route('seeker.applications.index')),
        ],
        [
            'q' => 'How do I post a job?',
            'keywords' => ['post', 'hire', 'hiring', 'employer', 'company', 'vacancy'],
            'a' => 'Create an account and choose "I am hiring". Then click Post a Job in the top menu and fill in the details.',
            'link' => $linkFor('employer', 'Post a job', route('employer.jobs.create')),
        ],
        [
            'q' => 'How do I see who applied?',
            'keywords' => ['applicant', 'who applied', 'candidate', 'download resume'],
            'a' => 'Employers can open My Jobs and click the people button next to a job. From there you can download each resume and update the status.',
            'link' => $linkFor('employer', 'Open my jobs', route('employer.jobs.index')),
        ],
        [
            'q' => 'How do I create an account?',
            'keywords' => ['register', 'sign up', 'signup', 'account', 'create', 'join'],
            'a' => 'Click Sign up, choose "I want a job" or "I am hiring", then enter your details.',
            'link' => auth()->check() ? null : ['text' => 'Sign up', 'url' => route('register')],
        ],
        [
            'q' => 'How do I change my password?',
            'keywords' => ['password', 'forgot', 'login problem', 'change email'],
            'a' => 'Go to Profile and fill in the Change password section. You need your current password, because this project has no "forgot password" email yet.',
            'link' => auth()->check() ? ['text' => 'Open my profile', 'url' => route('profile.edit')] : null,
        ],
        [
            'q' => 'Is my data safe here?',
            'keywords' => ['safe', 'data', 'real', 'private', 'privacy', 'demo', 'delete'],
            'a' => 'This is a student demo project, and its data can reset at any time. Please don\'t enter real personal details or upload your real resume.',
            'link' => null,
        ],
    ];
@endphp

<style>
    .faq-bot-toggle { position: fixed; right: 16px; bottom: 16px; z-index: 1040; width: 56px; height: 56px; border: 0; border-radius: 50%;
                      color: #fff; font-size: 1.5rem; background: linear-gradient(135deg, #0d6efd, #6f42c1); box-shadow: 0 .5rem 1rem rgba(0,0,0,.25); }
    .faq-bot-panel { position: fixed; right: 16px; bottom: 84px; z-index: 1040; width: 350px; max-width: calc(100vw - 32px);
                     height: 480px; max-height: calc(100vh - 170px); display: flex; flex-direction: column; overflow: hidden; }
    .faq-bot-panel[hidden] { display: none; }
    .faq-bot-header { background: linear-gradient(135deg, #0d6efd, #6f42c1); color: #fff; }
    .faq-bot-messages { flex: 1; overflow-y: auto; background: #f4f6fb; }
    .faq-bot-msg { max-width: 85%; padding: .5rem .75rem; border-radius: .75rem; margin-bottom: .5rem; font-size: .875rem; overflow-wrap: anywhere; }
    .faq-bot-msg.bot { background: #fff; box-shadow: 0 1px 2px rgba(0,0,0,.08); }
    .faq-bot-msg.user { background: #0d6efd; color: #fff; margin-left: auto; }
</style>

<button type="button" class="faq-bot-toggle" id="faqBotToggle" aria-label="Open help bot" aria-expanded="false" aria-controls="faqBotPanel">
    <i class="bi bi-chat-dots-fill"></i>
</button>

<section class="card border-0 shadow faq-bot-panel" id="faqBotPanel" aria-label="JobBoard help bot" hidden>
    <div class="faq-bot-header d-flex justify-content-between align-items-center px-3 py-2">
        <div>
            <div class="fw-bold">JobBoard Help</div>
            <small class="opacity-75">Answers to common questions</small>
        </div>
        <button type="button" class="btn-close btn-close-white" id="faqBotClose" aria-label="Close help bot"></button>
    </div>
    <div class="faq-bot-messages p-3" id="faqBotMessages" aria-live="polite"></div>
    <form class="d-flex gap-2 p-2 border-top bg-white" id="faqBotForm">
        <input type="text" class="form-control form-control-sm" id="faqBotInput" maxlength="200" autocomplete="off" placeholder="Type your question" aria-label="Your question">
        <button class="btn btn-primary btn-sm" aria-label="Send"><i class="bi bi-send"></i></button>
    </form>
</section>

<script>
(function () {
    const faqs = @js($faqs);
    const panel = document.getElementById('faqBotPanel');
    const toggle = document.getElementById('faqBotToggle');
    const messages = document.getElementById('faqBotMessages');
    const input = document.getElementById('faqBotInput');

    // textContent keeps whatever the visitor types from running as HTML
    function addMessage(text, who, link) {
        const bubble = document.createElement('div');
        bubble.className = 'faq-bot-msg ' + who;
        bubble.textContent = text;
        if (link) {
            const a = document.createElement('a');
            a.href = link.url;
            a.textContent = link.text;
            a.className = 'd-block mt-1 fw-semibold';
            bubble.appendChild(a);
        }
        messages.appendChild(bubble);
        return bubble;
    }

    function addQuestionButtons() {
        const wrap = document.createElement('div');
        wrap.className = 'd-flex flex-wrap gap-1 mb-2';
        faqs.forEach(function (faq) {
            const b = document.createElement('button');
            b.type = 'button';
            b.className = 'btn btn-outline-primary btn-sm bg-white text-primary text-start';
            b.textContent = faq.q;
            b.addEventListener('click', function () {
                addMessage(faq.q, 'user');
                answer(faq);
            });
            wrap.appendChild(b);
        });
        messages.appendChild(wrap);
    }

    function answer(faq) {
        const bubble = addMessage(faq.a, 'bot', faq.link);
        messages.scrollTop = bubble.offsetTop - messages.offsetTop - 8;
    }

    // Picks the FAQ with the most keywords found in the question
    function findAnswer(question) {
        const text = question.toLowerCase();
        let best = null;
        let bestScore = 0;
        faqs.forEach(function (faq) {
            const score = faq.keywords.filter(function (k) { return text.includes(k); }).length;
            if (score > bestScore) {
                best = faq;
                bestScore = score;
            }
        });
        return best;
    }

    function setOpen(open) {
        panel.hidden = !open;
        toggle.setAttribute('aria-expanded', String(open));
        toggle.setAttribute('aria-label', open ? 'Close help bot' : 'Open help bot');
        if (open && !messages.hasChildNodes()) {
            addMessage('Hi! I can answer common questions about JobBoard. Pick one below or type your own.', 'bot');
            addQuestionButtons();
        }
        if (open) {
            input.focus();
        } else {
            toggle.focus();
        }
    }

    toggle.addEventListener('click', function () { setOpen(panel.hidden); });
    document.getElementById('faqBotClose').addEventListener('click', function () { setOpen(false); });
    panel.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            setOpen(false);
        }
    });

    document.getElementById('faqBotForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const question = input.value.trim();
        if (!question) {
            return;
        }
        input.value = '';
        addMessage(question, 'user');
        const faq = findAnswer(question);
        if (faq) {
            answer(faq);
        } else {
            const bubble = addMessage('Sorry, I don\'t know that one yet. I can only answer common questions about using JobBoard. Try one of these:', 'bot');
            addQuestionButtons();
            messages.scrollTop = bubble.offsetTop - messages.offsetTop - 8;
        }
    });
})();
</script>
