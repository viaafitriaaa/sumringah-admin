@props(['name', 'extension' => 'svg'])

<img src="{{ asset('customer/'.$name.'.'.$extension) }}" alt="" aria-hidden="true" {{ $attributes->class(['size-5 shrink-0 object-contain']) }}>
