<?php
use Livewire\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app')] class extends Component
{
    public bool $sensorOnline = true;
    public float $ph = 7.2;
    public float $nutrisi = 1200;
    public float $kekeruhan = 45.5;
    public float $kelembaban = 72.0;
    public float $pwmPompa = 80;
    public array $labels = [];
    public array $phHistory = [];
    public array $nutrisiHistory = [];
    public array $kekeruhanHistory = [];
    public array $kelembabanHistory = [];

    public function mount(): void
    {
        $this->generateDummy();
    }

    public function generateDummy(): void
    {
        $this->sensorOnline = (bool) rand(0, 1);
        $this->ph = round(rand(60, 80) / 10, 1);
        $this->nutrisi = rand(800, 1500);
        $this->kekeruhan = rand(10, 100);
        $this->kelembaban = rand(50, 90);
        $this->pwmPompa = rand(0, 100);
        $this->labels = [];
        $this->phHistory = [];
        $this->nutrisiHistory = [];
        $this->kekeruhanHistory = [];
        $this->kelembabanHistory = [];
        for ($i = 10; $i >= 0; $i--) {
            $this->labels[] = now()->subMinutes($i * 5)->format('H:i');
            $this->phHistory[] = round(rand(60, 80) / 10, 1);
            $this->nutrisiHistory[] = rand(800, 1500);
            $this->kekeruhanHistory[] = rand(10, 100);
            $this->kelembabanHistory[] = rand(50, 90);
        }
    }

    public function refresh(): void
    {
        $this->generateDummy();
    }
};
?>
<div>
    <x-header :title="auth()->user()->hasRole('admin') ? 'Dashboard Admin' : 'Dashboard'" separator>
        <x-slot:actions>
            <x-button label='Refresh' icon='o-arrow-path' wire:click='refresh' class='btn-primary btn-sm' />
        </x-slot:actions>
    </x-header>

    {{-- STATUS SENSOR --}}
    <div class='grid grid-cols-1 gap-4 mb-6'>
        <div class='stat bg-base-100 rounded-box shadow'>
            <div class='stat-figure text-{{ $sensorOnline ? "success" : "error" }}'>
                <x-icon name='o-signal' class='w-8 h-8' />
            </div>
            <div class='stat-title'>Status Sensor</div>
            <div class='stat-value text-{{ $sensorOnline ? "success" : "error" }}'>
                {{ $sensorOnline ? 'Online' : 'Offline' }}
            </div>
            <div class='stat-desc'>{{ now()->format('d M Y H:i') }}</div>
        </div>
    </div>

    {{-- KARTU SENSOR --}}
    <div class='grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6'>
        <div class='stat bg-base-100 rounded-box shadow'>
            <div class='stat-figure text-primary'><x-icon name='o-beaker' class='w-8 h-8' /></div>
            <div class='stat-title'>pH Air</div>
            <div class='stat-value text-primary'>{{ $ph }}</div>
            <div class='stat-desc'>Normal: 6.0 - 7.5</div>
        </div>
        <div class='stat bg-base-100 rounded-box shadow'>
            <div class='stat-figure text-success'><x-icon name='o-cpu-chip' class='w-8 h-8' /></div>
            <div class='stat-title'>Nutrisi (ppm)</div>
            <div class='stat-value text-success'>{{ $nutrisi }}</div>
            <div class='stat-desc'>Normal: 800 - 1500</div>
        </div>
        <div class='stat bg-base-100 rounded-box shadow'>
            <div class='stat-figure text-warning'><x-icon name='o-eye' class='w-8 h-8' /></div>
            <div class='stat-title'>Kekeruhan (NTU)</div>
            <div class='stat-value text-warning'>{{ $kekeruhan }}</div>
            <div class='stat-desc'>Normal: &lt; 50 NTU</div>
        </div>
        <div class='stat bg-base-100 rounded-box shadow'>
            <div class='stat-figure text-info'><x-icon name='o-cloud' class='w-8 h-8' /></div>
            <div class='stat-title'>Kelembaban (%)</div>
            <div class='stat-value text-info'>{{ $kelembaban }}</div>
            <div class='stat-desc'>Normal: 60 - 80%</div>
        </div>
    </div>

    {{-- PWM POMPA --}}
    <div class='bg-base-100 rounded-box shadow p-4 mb-6'>
        <div class='flex justify-between mb-2'>
            <span class='font-bold'>PWM Pompa</span>
            <span class='font-bold text-primary'>{{ $pwmPompa }}%</span>
        </div>
        <progress class='progress progress-primary w-full' value='{{ $pwmPompa }}' max='100'></progress>
    </div>

    {{-- GRAFIK --}}
    <div class='grid grid-cols-1 lg:grid-cols-2 gap-4'>
        <div class='bg-base-100 rounded-box shadow p-4'>
            <h3 class='font-bold mb-3'>Grafik pH</h3>
            <canvas id='chartPh'></canvas>
        </div>
        <div class='bg-base-100 rounded-box shadow p-4'>
            <h3 class='font-bold mb-3'>Grafik Nutrisi</h3>
            <canvas id='chartNutrisi'></canvas>
        </div>
        <div class='bg-base-100 rounded-box shadow p-4'>
            <h3 class='font-bold mb-3'>Grafik Kekeruhan</h3>
            <canvas id='chartKekeruhan'></canvas>
        </div>
        <div class='bg-base-100 rounded-box shadow p-4'>
            <h3 class='font-bold mb-3'>Grafik Kelembaban</h3>
            <canvas id='chartKelembaban'></canvas>
        </div>
    </div>

    <script src='https://cdn.jsdelivr.net/npm/chart.js'></script>
    <script>
        function makeChart(id, label, labels, data, color) {
            new Chart(document.getElementById(id), {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{ label: label, data: data, borderColor: color, backgroundColor: color + '33', tension: 0.4, fill: true }]
                },
                options: { responsive: true, plugins: { legend: { display: false } } }
            });
        }
        makeChart('chartPh', 'pH', {{ json_encode($labels) }}, {{ json_encode($phHistory) }}, '#570df8');
        makeChart('chartNutrisi', 'Nutrisi', {{ json_encode($labels) }}, {{ json_encode($nutrisiHistory) }}, '#36d399');
        makeChart('chartKekeruhan', 'Kekeruhan', {{ json_encode($labels) }}, {{ json_encode($kekeruhanHistory) }}, '#fbbd23');
        makeChart('chartKelembaban', 'Kelembaban', {{ json_encode($labels) }}, {{ json_encode($kelembabanHistory) }}, '#3abff8');
    </script>
</div>