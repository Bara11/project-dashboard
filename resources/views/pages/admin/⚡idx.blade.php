<?php
use Livewire\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app')] class extends Component
{
    public bool $sensorOnline = false;
    public float $ph = 0;
    public float $nutrisi = 0;
    public float $kekeruhan = 0;
    public float $kelembaban = 0;
    public float $pwmPompa = 0;
    public float $suhuDs18b20 = 0;
    public float $suhuPinTo = 0;
    public array $labels = [];
    public array $phHistory = [];
    public array $nutrisiHistory = [];
    public array $kekeruhanHistory = [];
    public array $kelembabanHistory = [];
    public array $suhuDs18b20History = [];
    public array $suhuPinToHistory = [];

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
        $this->suhuDs18b20 = round(rand(240, 320) / 10, 1);
        $this->suhuPinTo = round(rand(230, 310) / 10, 1);
        $this->labels = [];
        $this->phHistory = [];
        $this->nutrisiHistory = [];
        $this->kekeruhanHistory = [];
        $this->kelembabanHistory = [];
        $this->suhuDs18b20History = [];
        $this->suhuPinToHistory = [];
        for ($i = 10; $i >= 0; $i--) {
            $this->labels[] = now()->subMinutes($i * 5)->format('H:i');
            $this->phHistory[] = round(rand(60, 80) / 10, 1);
            $this->nutrisiHistory[] = rand(800, 1500);
            $this->kekeruhanHistory[] = rand(10, 100);
            $this->kelembabanHistory[] = rand(50, 90);
            $this->suhuDs18b20History[] = round(rand(240, 320) / 10, 1);
            $this->suhuPinToHistory[] = round(rand(230, 310) / 10, 1);
        }
    }

    public function refresh(): void
    {
        $this->generateDummy();
    }

    public function setPwm(float $value): void
    {
        $this->pwmPompa = max(0, min(100, $value));
        $this->dispatch('pwm-updated', value: $this->pwmPompa);
    }
};
?>
<div style="padding: 16px; background: #f8fafb; min-height: 100vh;">

    {{-- TOPBAR --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <div>
            <div style="font-size: 15px; font-weight: 500; color: #111;">
                Hidroponik IoT — {{ auth()->user()->hasRole('admin') ? 'Dashboard Admin' : 'Dashboard' }}
            </div>
            <div style="font-size: 12px; color: #888;">Terakhir diperbarui: {{ now()->format('d M Y, H:i') }}</div>
        </div>
        <div style="display: flex; align-items: center; gap: 10px;">
            @if($sensorOnline)
                <span style="background:#EAF3DE; color:#3B6D11; font-size:11px; padding:3px 10px; border-radius:12px; display:inline-flex; align-items:center; gap:5px;">
                    <span style="width:7px;height:7px;border-radius:50%;background:#639922;display:inline-block;"></span>
                    Sensor Online
                </span>
            @else
                <span style="background:#FCEBEB; color:#A32D2D; font-size:11px; padding:3px 10px; border-radius:12px;">
                    Sensor Offline
                </span>
            @endif
            <x-button label="Refresh" icon="o-arrow-path" wire:click="refresh" class="btn-sm btn-ghost" />
        </div>
    </div>

    {{-- STAT CARDS --}}
    <div style="font-size:11px; font-weight:500; color:#888; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:8px;">Ringkasan Sensor</div>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); gap: 8px; margin-bottom: 8px;">

        <div style="background:#fff; border:0.5px solid #e5e7eb; border-radius:12px; padding:12px 14px; position:relative; overflow:hidden;">
            <div style="position:absolute;left:0;top:0;bottom:0;width:3px;background:#378ADD;border-radius:4px 0 0 4px;"></div>
            <div style="font-size:11px;color:#888;margin-bottom:4px;">pH Air</div>
            <div style="font-size:22px;font-weight:500;color:#185FA5;">{{ $ph }}</div>
            <div style="font-size:11px;color:#888;margin-top:2px;">Skala 0–14</div>
            <span style="font-size:10px;margin-top:6px;padding:2px 7px;border-radius:8px;display:inline-block;{{ $ph >= 6 && $ph <= 7.5 ? 'background:#EAF3DE;color:#3B6D11;' : 'background:#FAEEDA;color:#854F0B;' }}">
                {{ $ph >= 6 && $ph <= 7.5 ? 'Normal' : 'Periksa' }}
            </span>
        </div>

        <div style="background:#fff; border:0.5px solid #e5e7eb; border-radius:12px; padding:12px 14px; position:relative; overflow:hidden;">
            <div style="position:absolute;left:0;top:0;bottom:0;width:3px;background:#1D9E75;border-radius:4px 0 0 4px;"></div>
            <div style="font-size:11px;color:#888;margin-bottom:4px;">Nutrisi</div>
            <div style="font-size:22px;font-weight:500;color:#0F6E56;">{{ number_format($nutrisi) }}</div>
            <div style="font-size:11px;color:#888;margin-top:2px;">ppm</div>
            <span style="font-size:10px;margin-top:6px;padding:2px 7px;border-radius:8px;display:inline-block;{{ $nutrisi >= 800 && $nutrisi <= 1500 ? 'background:#EAF3DE;color:#3B6D11;' : 'background:#FCEBEB;color:#A32D2D;' }}">
                {{ $nutrisi >= 800 && $nutrisi <= 1500 ? 'Normal' : 'Periksa' }}
            </span>
        </div>

        <div style="background:#fff; border:0.5px solid #e5e7eb; border-radius:12px; padding:12px 14px; position:relative; overflow:hidden;">
            <div style="position:absolute;left:0;top:0;bottom:0;width:3px;background:#EF9F27;border-radius:4px 0 0 4px;"></div>
            <div style="font-size:11px;color:#888;margin-bottom:4px;">Kekeruhan</div>
            <div style="font-size:22px;font-weight:500;color:#854F0B;">{{ $kekeruhan }}</div>
            <div style="font-size:11px;color:#888;margin-top:2px;">NTU</div>
            <span style="font-size:10px;margin-top:6px;padding:2px 7px;border-radius:8px;display:inline-block;{{ $kekeruhan <= 50 ? 'background:#EAF3DE;color:#3B6D11;' : 'background:#FAEEDA;color:#854F0B;' }}">
                {{ $kekeruhan <= 50 ? 'Normal' : 'Tinggi' }}
            </span>
        </div>

        <div style="background:#fff; border:0.5px solid #e5e7eb; border-radius:12px; padding:12px 14px; position:relative; overflow:hidden;">
            <div style="position:absolute;left:0;top:0;bottom:0;width:3px;background:#378ADD;border-radius:4px 0 0 4px;"></div>
            <div style="font-size:11px;color:#888;margin-bottom:4px;">Kelembaban</div>
            <div style="font-size:22px;font-weight:500;color:#185FA5;">{{ $kelembaban }}</div>
            <div style="font-size:11px;color:#888;margin-top:2px;">%</div>
            <span style="font-size:10px;margin-top:6px;padding:2px 7px;border-radius:8px;display:inline-block;{{ $kelembaban >= 60 && $kelembaban <= 80 ? 'background:#EAF3DE;color:#3B6D11;' : 'background:#FAEEDA;color:#854F0B;' }}">
                {{ $kelembaban >= 60 && $kelembaban <= 80 ? 'Normal' : 'Periksa' }}
            </span>
        </div>

        <div style="background:#fff; border:0.5px solid #e5e7eb; border-radius:12px; padding:12px 14px; position:relative; overflow:hidden;">
            <div style="position:absolute;left:0;top:0;bottom:0;width:3px;background:#D85A30;border-radius:4px 0 0 4px;"></div>
            <div style="font-size:11px;color:#888;margin-bottom:4px;">Suhu DS18B20</div>
            <div style="font-size:22px;font-weight:500;color:#993C1D;">{{ $suhuDs18b20 }}°C</div>
            <div style="font-size:11px;color:#888;margin-top:2px;">Sensor suhu air</div>
            <span style="font-size:10px;margin-top:6px;padding:2px 7px;border-radius:8px;display:inline-block;{{ $suhuDs18b20 >= 20 && $suhuDs18b20 <= 30 ? 'background:#EAF3DE;color:#3B6D11;' : 'background:#FAEEDA;color:#854F0B;' }}">
                {{ $suhuDs18b20 >= 20 && $suhuDs18b20 <= 30 ? 'Normal' : 'Periksa' }}
            </span>
        </div>

        <div style="background:#fff; border:0.5px solid #e5e7eb; border-radius:12px; padding:12px 14px; position:relative; overflow:hidden;">
            <div style="position:absolute;left:0;top:0;bottom:0;width:3px;background:#F09595;border-radius:4px 0 0 4px;"></div>
            <div style="font-size:11px;color:#888;margin-bottom:4px;">Suhu Pin To</div>
            <div style="font-size:22px;font-weight:500;color:#A32D2D;">{{ $suhuPinTo }}°C</div>
            <div style="font-size:11px;color:#888;margin-top:2px;">Sensor pH pin</div>
            <span style="font-size:10px;margin-top:6px;padding:2px 7px;border-radius:8px;display:inline-block;{{ $suhuPinTo >= 20 && $suhuPinTo <= 30 ? 'background:#EAF3DE;color:#3B6D11;' : 'background:#FAEEDA;color:#854F0B;' }}">
                {{ $suhuPinTo >= 20 && $suhuPinTo <= 30 ? 'Normal' : 'Periksa' }}
            </span>
        </div>

    </div>

    {{-- PWM POMPA CONTROL --}}
    <div style="background:#fff; border:0.5px solid #e5e7eb; border-radius:12px; padding:14px 16px; margin-bottom:8px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
            <div>
                <div style="font-size:13px;font-weight:500;color:#111;">PWM Pompa</div>
                <div style="font-size:11px;color:#888;">Atur kecepatan pompa</div>
            </div>
            <span style="font-size:20px;font-weight:500;color:#185FA5;">{{ $pwmPompa }}%</span>
        </div>
        <div style="background:#E6F1FB; border-radius:4px; height:8px; margin-bottom:12px;">
            <div style="background: linear-gradient(90deg, #378ADD, #1D9E75); height:8px; border-radius:4px; width:{{ $pwmPompa }}%; transition: width 0.3s;"></div>
        </div>
        @role('admin')
        <div style="display:flex; gap:8px; align-items:center;">
            <span style="font-size:11px;color:#888;">0%</span>
            <input type="range" min="0" max="100" step="1" value="{{ $pwmPompa }}"
                style="flex:1;"
                wire:change="setPwm($event.target.value)" />
            <span style="font-size:11px;color:#888;">100%</span>
        </div>
        <div style="display:flex; gap:6px; margin-top:10px; flex-wrap:wrap;">
            <x-button label="0%" wire:click="setPwm(0)" class="btn-xs btn-outline" />
            <x-button label="25%" wire:click="setPwm(25)" class="btn-xs btn-outline" />
            <x-button label="50%" wire:click="setPwm(50)" class="btn-xs btn-outline" />
            <x-button label="75%" wire:click="setPwm(75)" class="btn-xs btn-outline" />
            <x-button label="100%" wire:click="setPwm(100)" class="btn-xs btn-outline" />
        </div>
        @endrole
    </div>

    {{-- GRAFIK --}}
    <div style="font-size:11px; font-weight:500; color:#888; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:8px; margin-top:16px;">Grafik Historis</div>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 8px;">

        <div style="background:#fff; border:0.5px solid #e5e7eb; border-radius:12px; padding:12px 14px;">
            <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                <span style="font-size:12px;color:#888;">pH Air</span>
                <span style="font-size:14px;font-weight:500;color:#185FA5;">{{ $ph }}</span>
            </div>
            <div style="position:relative;height:120px;"><canvas id="chartPh" role="img" aria-label="Grafik pH">pH historis</canvas></div>
        </div>

        <div style="background:#fff; border:0.5px solid #e5e7eb; border-radius:12px; padding:12px 14px;">
            <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                <span style="font-size:12px;color:#888;">Nutrisi (ppm)</span>
                <span style="font-size:14px;font-weight:500;color:#0F6E56;">{{ number_format($nutrisi) }}</span>
            </div>
            <div style="position:relative;height:120px;"><canvas id="chartNutrisi" role="img" aria-label="Grafik nutrisi">Nutrisi historis</canvas></div>
        </div>

        <div style="background:#fff; border:0.5px solid #e5e7eb; border-radius:12px; padding:12px 14px;">
            <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                <span style="font-size:12px;color:#888;">Kekeruhan (NTU)</span>
                <span style="font-size:14px;font-weight:500;color:#854F0B;">{{ $kekeruhan }}</span>
            </div>
            <div style="position:relative;height:120px;"><canvas id="chartKekeruhan" role="img" aria-label="Grafik kekeruhan">Kekeruhan historis</canvas></div>
        </div>

        <div style="background:#fff; border:0.5px solid #e5e7eb; border-radius:12px; padding:12px 14px;">
            <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                <span style="font-size:12px;color:#888;">Kelembaban (%)</span>
                <span style="font-size:14px;font-weight:500;color:#185FA5;">{{ $kelembaban }}</span>
            </div>
            <div style="position:relative;height:120px;"><canvas id="chartKelembaban" role="img" aria-label="Grafik kelembaban">Kelembaban historis</canvas></div>
        </div>

        {{-- SUHU - 2 LINE DALAM 1 CHART --}}
        <div style="background:#fff; border:0.5px solid #e5e7eb; border-radius:12px; padding:12px 14px; grid-column: span 2;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                <span style="font-size:12px;color:#888;">Suhu (°C)</span>
                <div style="display:flex; gap:12px;">
                    <span style="display:flex;align-items:center;gap:4px;font-size:11px;color:#888;">
                        <span style="width:10px;height:3px;background:#D85A30;border-radius:2px;display:inline-block;"></span>DS18B20: {{ $suhuDs18b20 }}°C
                    </span>
                    <span style="display:flex;align-items:center;gap:4px;font-size:11px;color:#888;">
                        <span style="width:10px;height:3px;background:#F09595;border-radius:2px;display:inline-block;"></span>Pin To: {{ $suhuPinTo }}°C
                    </span>
                </div>
            </div>
            <div style="position:relative;height:160px;"><canvas id="chartSuhu" role="img" aria-label="Grafik suhu DS18B20 dan Pin To">Suhu historis</canvas></div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const chartOpts = () => ({
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { font: { size: 10 }, color: '#888' } },
                y: { grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { font: { size: 10 }, color: '#888' } }
            },
            elements: { point: { radius: 2 } }
        });
        const mk = (id, data, color) => new Chart(document.getElementById(id), {
            type: 'line',
            data: { labels: @json($labels), datasets: [{ data, borderColor: color, backgroundColor: color + '15', tension: 0.4, fill: true, borderWidth: 1.5 }] },
            options: chartOpts()
        });
        mk('chartPh', @json($phHistory), '#378ADD');
        mk('chartNutrisi', @json($nutrisiHistory), '#1D9E75');
        mk('chartKekeruhan', @json($kekeruhanHistory), '#EF9F27');
        mk('chartKelembaban', @json($kelembabanHistory), '#378ADD');

        new Chart(document.getElementById('chartSuhu'), {
            type: 'line',
            data: {
                labels: @json($labels),
                datasets: [
                    { label: 'DS18B20', data: @json($suhuDs18b20History), borderColor: '#D85A30', backgroundColor: '#D85A3015', tension: 0.4, fill: false, borderWidth: 1.5, pointRadius: 2 },
                    { label: 'Pin To', data: @json($suhuPinToHistory), borderColor: '#F09595', backgroundColor: '#F0959515', tension: 0.4, fill: false, borderWidth: 1.5, borderDash: [4,3], pointRadius: 2 }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { font: { size: 10 }, color: '#888' } },
                    y: { grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { font: { size: 10 }, color: '#888' } }
                },
                elements: { point: { radius: 2 } }
            }
        });
    </script>

    <style>
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.3} }
    </style>
</div>
