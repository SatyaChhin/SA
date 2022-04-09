@extends(backpack_view('blank'))

@php
$widgets['before_content'][] = [
    'type' => 'div',
    'class' => 'row',
    'content' => [
        [
            'type' => 'progress_white',
            'class' => 'card mb-2 ',
            'value' => $student,
            'description' => 'Registered Student <i class="las la-chalkboard-teacher dashboard"></i>',
            'progress' => $student, 
            'hint' => '8544 more until next milestone.',
        ],
        [
            'type' => 'progress_white',
            'class' => 'card mb-2',
            'value' => $teacher,
            'description' => 'Registered Teacher.',
            
            'progressClass' => 'progress-bar bg-danger',
            'progress' => $teacher,
            'hint' => '8544 more until next milestone.',
        ],
        [
            'type' => 'progress_white',
            'class' => 'card mb-2',
            'value' => $subject,
            'description' => 'Registered Subject.',
            'progress' => $subject,
            'progressClass' => 'progress-bar bg-warning',
        
            'hint' => '8544 more until next milestone.',
        ],
        [
            'type' => 'progress_white',
            'class' => 'card mb-2',
            'value' => $student + $teacher,
            'description' => 'Registered Member.',
            'progress' => $subject,
            'progressClass' => 'progress-bar bg-info',
            'hint' => '8544 more until next milestone.',
        ],
    ],
];
@endphp

@section('content')
@endsection
