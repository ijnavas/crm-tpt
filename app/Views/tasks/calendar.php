<?php
// Preparar tareas agrupadas por día para el JS
$tasksByDay = [];
foreach ($tasks as $task) {
    if (empty($task['due_date'])) continue;
    $day = date('Y-m-d', strtotime($task['due_date']));
    $tasksByDay[$day][] = [
        'id'     => $task['id'],
        'title'  => $task['title'],
        'type'   => $task['type'] ?? 'otro',
        'status' => $task['status'],
        'entity' => $task['entity_name'] ?? '',
    ];
}
?>
<style>
.cal-wrap{padding:0}
.cal-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px}
.cal-header-left h1{margin:0 0 3px;font-size:22px;font-weight:700;color:#1a2b4a}
.cal-header-left p{margin:0;font-size:13px;color:#6b7280}
.cal-nav{display:flex;gap:8px;align-items:center}
.cal-nav-btn{height:34px;padding:0 14px;background:#fff;border:1px solid var(--border);border-radius:8px;font-size:13px;cursor:pointer;color:var(--text-main);font-weight:600}
.cal-nav-btn:hover{background:var(--bg-muted)}
.cal-month-label{font-size:15px;font-weight:700;color:var(--text-main);min-width:160px;text-align:center}
.cal-tabs{display:flex;gap:6px}
.cal-tab{height:34px;padding:0 14px;border:1px solid var(--border);border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;color:var(--text-soft);text-decoration:none;display:inline-flex;align-items:center;gap:6px;background:#fff}
.cal-tab.active{background:var(--primary);color:#fff;border-color:var(--primary)}
.cal-tab:hover:not(.active){background:var(--bg-muted)}

.cal-grid{display:grid;grid-template-columns:repeat(7,1fr);gap:1px;background:var(--border);border:1px solid var(--border);border-radius:12px;overflow:hidden;box-shadow:var(--shadow-soft)}
.cal-day-header{background:#f8fafc;padding:10px 0;text-align:center;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.5px}
.cal-day{background:#fff;padding:8px;min-height:100px;cursor:pointer;transition:background .1s;position:relative}
.cal-day:hover{background:#f8fafc}
.cal-day.other-month{background:#f9fafb}
.cal-day.other-month .day-num{color:#d1d5db}
.cal-day.today{background:#eff6ff}
.cal-day.today .day-num-inner{width:24px;height:24px;border-radius:50%;background:#1a6ed8;color:#fff;display:flex;align-items:center;justify-content:center}
.day-num{font-size:12px;font-weight:600;color:#374151;margin-bottom:5px}
.task-pill{font-size:10px;padding:2px 6px;border-radius:4px;margin-bottom:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;cursor:pointer;display:block}
.task-pill:hover{filter:brightness(.93)}
.tp-llamada{background:#dbeafe;color:#1e40af}
.tp-email{background:#dcfce7;color:#166534}
.tp-visita{background:#ede9fe;color:#5b21b6}
.tp-propuesta{background:#fef3c7;color:#92400e}
.tp-reunion{background:#fce7f3;color:#9d174d}
.tp-seguimiento{background:#e0f2fe;color:#075985}
.tp-otro{background:#f3f4f6;color:#374151}
.tp-overdue{background:#fee2e2;color:#991b1b}
.more-tasks{font-size:10px;color:#9ca3af;padding:1px 4px;cursor:pointer}
.more-tasks:hover{color:var(--primary)}

.cal-legend{display:flex;gap:14px;margin-top:14px;flex-wrap:wrap}
.legend-item{display:flex;align-items:center;gap:5px;font-size:12px;color:#6b7280}
.legend-dot{width:10px;height:10px;border-radius:3px;flex-shrink:0}

/* Tooltip */
.cal-tooltip{position:fixed;background:#1e293b;color:#fff;border-radius:8px;padding:10px 14px;font-size:12px;max-width:220px;z-index:9999;pointer-events:none;display:none;box-shadow:0 4px 20px rgba(0,0,0,.25)}
.cal-tooltip-title{font-weight:600;margin-bottom:4px}
.cal-tooltip-meta{color:#94a3b8;font-size:11px}

@media(max-width:600px){
  .cal-day{min-height:60px;padding:4px}
  .task-pill{display:none}
  .day-num{font-size:11px}
}
</style>

<div class="cal-wrap">
    <div class="cal-header">
        <div class="cal-header-left">
            <h1>Tareas</h1>
            <p>Seguimiento comercial</p>
        </div>
        <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
            <div class="cal-nav">
                <button class="cal-nav-btn" onclick="changeMonth(-1)">&#8592;</button>
                <span class="cal-month-label" id="cal-month-label"></span>
                <button class="cal-nav-btn" onclick="changeMonth(1)">&#8594;</button>
                <button class="cal-nav-btn" onclick="goToday()">Hoy</button>
            </div>
            <div class="cal-tabs">
                <a href="/tasks?view=list" class="cal-tab">☰ Lista</a>
                <a href="/tasks?view=grouped" class="cal-tab">⊞ Empresa</a>
                <span class="cal-tab active">📅 Calendario</span>
                <a href="/tasks/create" class="btn btn-primary" style="height:34px;display:inline-flex;align-items:center;padding:0 14px;font-size:13px">+ Nueva</a>
            </div>
        </div>
    </div>

    <div class="cal-grid" id="cal-grid">
        <?php foreach (['Lun','Mar','Mié','Jue','Vie','Sáb','Dom'] as $d): ?>
        <div class="cal-day-header"><?= $d ?></div>
        <?php endforeach; ?>
    </div>

    <div class="cal-legend">
        <div class="legend-item"><div class="legend-dot" style="background:#dbeafe"></div>Llamada</div>
        <div class="legend-item"><div class="legend-dot" style="background:#dcfce7"></div>Email</div>
        <div class="legend-item"><div class="legend-dot" style="background:#ede9fe"></div>Visita</div>
        <div class="legend-item"><div class="legend-dot" style="background:#fef3c7"></div>Propuesta</div>
        <div class="legend-item"><div class="legend-dot" style="background:#fce7f3"></div>Reunión</div>
        <div class="legend-item"><div class="legend-dot" style="background:#fee2e2"></div>Vencida</div>
    </div>
</div>

<div class="cal-tooltip" id="cal-tooltip">
    <div class="cal-tooltip-title" id="tt-title"></div>
    <div class="cal-tooltip-meta" id="tt-meta"></div>
</div>

<script>
const MONTHS = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
const TODAY = new Date();
let current = new Date(TODAY.getFullYear(), TODAY.getMonth(), 1);

const TASKS_DATA = <?= json_encode($tasksByDay) ?>;

function pad(n){ return String(n).padStart(2,'0'); }
function dateKey(y,m,d){ return y+'-'+pad(m+1)+'-'+pad(d); }

function getTaskClass(task) {
    const isOverdue = task.status !== 'completada' && task.due_date < new Date().toISOString().slice(0,10);
    if (isOverdue) return 'tp-overdue';
    return 'tp-' + (task.type || 'otro');
}

function renderCalendar() {
    const grid = document.getElementById('cal-grid');
    const headers = Array.from(grid.querySelectorAll('.cal-day-header'));
    grid.innerHTML = '';
    headers.forEach(h => grid.appendChild(h));

    document.getElementById('cal-month-label').textContent = MONTHS[current.getMonth()] + ' ' + current.getFullYear();

    const firstDay = new Date(current.getFullYear(), current.getMonth(), 1);
    let startDow = firstDay.getDay();
    startDow = startDow === 0 ? 6 : startDow - 1;

    const daysInMonth = new Date(current.getFullYear(), current.getMonth()+1, 0).getDate();
    const prevDays = new Date(current.getFullYear(), current.getMonth(), 0).getDate();
    const totalCells = Math.ceil((startDow + daysInMonth) / 7) * 7;

    for (let i = 0; i < totalCells; i++) {
        const cell = document.createElement('div');
        cell.className = 'cal-day';
        let dayNum, year = current.getFullYear(), month = current.getMonth(), isOther = false;

        if (i < startDow) {
            dayNum = prevDays - startDow + i + 1;
            month = current.getMonth() - 1;
            if (month < 0) { month = 11; year--; }
            isOther = true;
        } else if (i >= startDow + daysInMonth) {
            dayNum = i - startDow - daysInMonth + 1;
            month = current.getMonth() + 1;
            if (month > 11) { month = 0; year++; }
            isOther = true;
        } else {
            dayNum = i - startDow + 1;
        }

        if (isOther) cell.classList.add('other-month');

        const isToday = !isOther &&
            current.getFullYear() === TODAY.getFullYear() &&
            current.getMonth() === TODAY.getMonth() &&
            dayNum === TODAY.getDate();
        if (isToday) cell.classList.add('today');

        const numDiv = document.createElement('div');
        numDiv.className = 'day-num';
        if (isToday) {
            numDiv.innerHTML = '<span class="day-num-inner">' + dayNum + '</span>';
        } else {
            numDiv.textContent = dayNum;
        }
        cell.appendChild(numDiv);

        const key = dateKey(year, month, dayNum);
        const dayTasks = TASKS_DATA[key] || [];

        dayTasks.slice(0, 3).forEach(t => {
            const pill = document.createElement('a');
            pill.className = 'task-pill ' + (t.status !== 'completada' && key < new Date().toISOString().slice(0,10) ? 'tp-overdue' : 'tp-' + (t.type || 'otro'));
            pill.href = '/tasks/' + t.id + '/edit';
            pill.textContent = t.title;
            pill.title = t.title + (t.entity ? ' · ' + t.entity : '');
            pill.addEventListener('mouseenter', e => showTooltip(e, t));
            pill.addEventListener('mouseleave', hideTooltip);
            cell.appendChild(pill);
        });

        if (dayTasks.length > 3) {
            const more = document.createElement('div');
            more.className = 'more-tasks';
            more.textContent = '+' + (dayTasks.length - 3) + ' más';
            cell.appendChild(more);
        }

        grid.appendChild(cell);
    }
}

function showTooltip(e, task) {
    const tt = document.getElementById('cal-tooltip');
    document.getElementById('tt-title').textContent = task.title;
    document.getElementById('tt-meta').textContent = [task.type, task.entity, task.status].filter(Boolean).join(' · ');
    tt.style.display = 'block';
    tt.style.left = (e.clientX + 12) + 'px';
    tt.style.top = (e.clientY - 10) + 'px';
}
function hideTooltip() {
    document.getElementById('cal-tooltip').style.display = 'none';
}
document.addEventListener('mousemove', e => {
    const tt = document.getElementById('cal-tooltip');
    if (tt.style.display === 'block') {
        tt.style.left = (e.clientX + 12) + 'px';
        tt.style.top = (e.clientY - 10) + 'px';
    }
});

function changeMonth(dir) {
    current = new Date(current.getFullYear(), current.getMonth() + dir, 1);
    renderCalendar();
}
function goToday() {
    current = new Date(TODAY.getFullYear(), TODAY.getMonth(), 1);
    renderCalendar();
}

renderCalendar();
</script>