@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-surface text-text placeholder-muted border-border focus:border-primary focus:ring-primary rounded-md shadow-sm']) }}>
