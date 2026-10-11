{{-- New repeater rows are cloned from these; __INDEX__ is replaced in JS. --}}
<template id="faqRowTemplate">@include('admin.articles.faq-row', ['i' => '__INDEX__', 'faq' => []])</template>
<template id="sourceRowTemplate">@include('admin.articles.source-row', ['i' => '__INDEX__', 'source' => []])</template>
