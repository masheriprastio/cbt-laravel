@extends('layouts.flexy-blank')

@section('title','Demo Ujian')

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow-sm">
        <div class="card-body">
          <h4 class="card-title">Mode Ujian (Demo)</h4>
          <p class="text-muted small">Halaman demo ini tidak memerlukan autentikasi. Gunakan untuk menguji UI ujian siswa.</p>

          <div id="exam-area">
            {{-- Violation alert container --}}
            <div id="violation-alert-container"></div>
            <div class="mb-3">
              <strong>Timer:</strong> <span id="timer">00:00</span>
            </div>

            @if(isset($questions) && isset($test))
              <form id="answer-form" method="POST" action="{{ route('exam.demo.submit') }}">
                @csrf
                <input type="hidden" name="test_id" value="{{ $test->id }}">
                <input type="hidden" name="violations" id="violations" value="0">
                  <input type="hidden" name="session_id" id="session_id" value="">

                @foreach($questions as $q)
                  <div class="card mb-3">
                    <div class="card-body">
                      <h5 class="card-title">Soal {{ $q['number'] ?? $q['id'] }}</h5>
                      <p class="card-text">{!! nl2br(e($q['text'])) !!}</p>

                      @foreach($q['options'] as $opt)
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="answers[{{ $q['id'] }}]" id="choice_{{ $opt['id'] }}" value="{{ $opt['id'] }}" disabled>
                          <label class="form-check-label" for="choice_{{ $opt['id'] }}">{{ $opt['label'] }}. {{ $opt['text'] }}</label>
                        </div>
                      @endforeach
                    </div>
                  </div>
                @endforeach

                <div class="d-flex gap-2">
                  <button id="start-btn" type="button" class="btn btn-primary">Mulai Ujian</button>
                  <button id="submit-btn" type="submit" class="btn btn-success" disabled>Kumpulkan</button>
                </div>
              </form>

            @else
              <div class="card mb-3">
                <div class="card-body">
                  <h5 class="card-title">Soal {{ $question['id'] }}</h5>
                  <p class="card-text">{{ $question['text'] }}</p>

                  <form id="answer-form">
                    @foreach($question['choices'] as $key => $label)
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="answer" id="choice_{{ $key }}" value="{{ $key }}" disabled>
                        <label class="form-check-label" for="choice_{{ $key }}">{{ $key }}. {{ $label }}</label>
                      </div>
                    @endforeach
                  </form>
                </div>
              </div>

              <div class="d-flex gap-2">
                <button id="start-btn" class="btn btn-primary">Mulai Ujian</button>
                <button id="submit-btn" class="btn btn-success" disabled>Kumpulkan</button>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

  @push('scripts')
  <script>
    (function(){
      const startBtn = document.getElementById('start-btn');
      const submitBtn = document.getElementById('submit-btn');
      const timerEl = document.getElementById('timer');
      const radioButtons = document.querySelectorAll('#answer-form input[type=radio]');
      const violationContainer = document.getElementById('violation-alert-container');
      const violationsInput = document.getElementById('violations');
  let seconds = 0;
  let timerId = null;
  let violations = 0;
  let examEnded = false; // set true when exam is finished/submitted to stop counting violations
      const MAX_VIOLATIONS = 3;
      let autoSubmitting = false;

      function formatTime(s){
        const mm = String(Math.floor(s/60)).padStart(2,'0');
        const ss = String(s%60).padStart(2,'0');
        return mm+':'+ss;
      }

      function tick(){
        seconds++;
        timerEl.textContent = formatTime(seconds);
      }

      function showViolation(message, level = 'warning'){
        // simple bootstrap alert insert/replace
        violationContainer.innerHTML = `\n          <div class="alert alert-${level} alert-dismissible fade show" role="alert">\n            ${message}\n            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>\n          </div>`;
      }

      // server session tracking (optional) — create session when exam starts and report violations
      let sessionId = null;
      function getCsrfToken(){
        const el = document.querySelector('#answer-form input[name="_token"]');
        return el ? el.value : null;
      }

      function notifyServerStart(){
        const token = getCsrfToken();
        const testIdEl = document.querySelector('#answer-form input[name="test_id"]');
        const testId = testIdEl ? testIdEl.value : null;
        if (!testId) return Promise.resolve(null);
        return fetch('/exam/session/start', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token
          },
          body: JSON.stringify({ test_id: testId })
        }).then(r => r.ok ? r.json() : null).then(json => {
          if (json && json.id) {
            sessionId = json.id;
            const sidEl = document.getElementById('session_id');
            if (sidEl) sidEl.value = sessionId;
            console.log('Exam session started', json);
          }
          return json;
        }).catch(e => { console.error('start session error', e); return null; });
      }

      function reportViolationToServer(){
        if (!sessionId) return Promise.resolve(null);
        const token = getCsrfToken();
        return fetch('/exam/session/' + encodeURIComponent(sessionId) + '/violation', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token
          },
          body: JSON.stringify({})
        }).then(r => r.ok ? r.json() : null).then(json => {
          if (json) console.log('Violation reported, server count:', json.violations);
          return json;
        }).catch(e => { console.error('report violation error', e); return null; });
      }

      function handleViolation(){
        violations++;
        if (violationsInput) { violationsInput.value = violations; }

        console.log('Demo: visibility violation #'+violations);

        if (violations < MAX_VIOLATIONS) {
          const msg = `Peringatan pelanggaran: Anda berpindah tab (${violations}/${MAX_VIOLATIONS}). Jangan berpindah tab saat ujian.`;
          showViolation(msg,'warning');
          try { window.alert(msg); } catch (e) { /* ignore */ }
          // notify server (don't block)
          reportViolationToServer();
        } else {
          // reached max violations: show danger and auto-submit
          const finalMsg = `Pelanggaran ke-${violations}. Ujian akan dikumpulkan otomatis.`;
          showViolation(finalMsg,`danger`);
          try { window.alert(finalMsg); } catch (e) { /* ignore */ }
          if (!autoSubmitting) {
            autoSubmitting = true;
            clearInterval(timerId);
            radioButtons.forEach(r => r.disabled = false);
            // report final violation, then submit the form
            reportViolationToServer().then(()=>{
              const form = document.getElementById('answer-form');
              if (form && form.submit) {
                setTimeout(()=>{ form.submit(); }, 600);
              } else {
                radioButtons.forEach(r => r.disabled = true);
                submitBtn.disabled = true;
              }
            });
          }
        }
      }

      // Listen for visibility changes (tab switches)
      document.addEventListener('visibilitychange', function(){
        if (document.hidden) {
          // user switched away from tab
          if (timerId && !examEnded) { // only count violations after exam started and before exam ended
            handleViolation();
          }
        }
      });

      startBtn.addEventListener('click', function(){
        // enable choices
        radioButtons.forEach(r => r.disabled = false);
        startBtn.disabled = true;
        submitBtn.disabled = false;
        // start timer
        timerId = setInterval(tick, 1000);
        // reset violations
        violations = 0;
        if (violationsInput) { violationsInput.value = 0; }
        violationContainer.innerHTML = '';
        // notify server that a session has started (async)
        try { notifyServerStart(); } catch (e) { console.error(e); }
      });

      submitBtn.addEventListener('click', function(e){
        // If the submit button belongs to a form (type=submit), stop the timer but DO NOT
        // disable the inputs before submit, otherwise their values won't be posted.
        if (submitBtn.type === 'submit' && submitBtn.form) {
          // mark exam ended so visibilitychange won't count another violation during submit
          examEnded = true;
          clearInterval(timerId);
          // set violations input if present
          if (violationsInput) { violationsInput.value = violations; }
          // allow default form submit to include radio values
          return;
        }

        // fallback demo behaviour (no server submit): show selected answer in alert
        const sel = document.querySelector('#answer-form input[type=radio]:checked');
        const val = sel ? sel.value : '(tidak memilih)';
        alert('Jawaban dikumpulkan: ' + val + '\nWaktu: ' + formatTime(seconds) + '\nPelanggaran: ' + violations);
        // stop timer and disable inputs
        examEnded = true;
        clearInterval(timerId);
        radioButtons.forEach(r => r.disabled = true);
        submitBtn.disabled = true;
      });
    })();
  </script>
  @endpush

@endsection
