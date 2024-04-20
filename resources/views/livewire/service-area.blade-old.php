<div class="relative">
    <input type="text"
        class="w-8/12 duration-100 ease-in-out focus:outline-none focus:shadow-md border border-transparent focus:bg-gray-100  placeholder-gray-600 rounded-lg bg-gray-200 py-2  pl-10  appearance-none leading-normal"
        placeholder="ค้นหาอำเภอปลายทางที่ต้องการ..." wire:model="query" wire:keydown.escape="resetData"
        wire:keydown.tab="resetData" wire:keydown.arrow-up="decrementHighlight"
        wire:keydown.arrow-down="incrementHighlight" wire:keydown.enter="selectBrancharea" />

    <div wire:loading class="absolute z-10 w-full bg-white rounded-t-none shadow-lg list-group">
        <div class="list-item">กำลังค้นหา...</div>
    </div>

    @if (!empty($query))
        <div class="fixed top-0 bottom-0 left-0 right-0" wire:click="resetData"></div>

        <div class="absolute z-10 w-full bg-white rounded-t-none shadow-lg list-group">
            @if (!empty($branchareas))
                @foreach ($branchareas as $i => $brancharea)
                    <a href="/"
                        class="list-item {{ $highlightIndex === $i ? 'highlightIndex' : '' }}">{{ $brancharea['district'] }}</a>
                @endforeach
            @else
                <div class="list-item">ไม่อยู่ในพื้นที่บริการของสี่สหาย!</div>
            @endif
        </div>
    @endif
</div>
