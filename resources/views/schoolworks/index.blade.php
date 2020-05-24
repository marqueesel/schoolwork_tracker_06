@extends('layouts.layout')

@section('navcontent')
<div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
        <li class="nav-item">
            <a class="nav-link" href="/subjects">Subjects</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/schoolworks">Schoolworks</a>
        </li>
    </ul>
</div>
@endsection

@section('content')
<div>
    @if (Route::has('login'))
        <div class="top-right links">
            @auth
                <a href="{{ url('/home') }}">Home</a>
            @else
                <a href="{{ route('login') }}">Login</a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}">Register</a>
                @endif
            @endauth
        </div>
    @endif
    
    <div class="container h-100 mt-2">
        @if(session('mssg') == "danger")
            <div id="alert" class="alert alert-danger text-center" role="alert">
                A subject was deleted!
            </div>
        @elseif(session('mssg') == "success")
            <div id="alert" class="alert alert-success text-center" role="alert">
                A new subject was added!
            </div>
        @endif

        <h1 class="d-inline display-4">Schoolworks Management</h1>
        <a class="btn btn-success btn-lg text-white float-right d-inline mt-3 mr-5" type="button" data-toggle="modal" data-target="#createModal">New Schoolwork</a>
        @if(count($subjects) > 0)
        <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="creatSchoolwork" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createSchoolwork">Create a New Schoolwork</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="/schoolworks" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">Description:</label>
                                <input class="form-control" type="text" name="description" id="description">
                            </div>
                            <div class="form-group">
                                <label for="subject_id">Select Subject:</label>
                                <select id="subject_id" name="subject_id" class="form-control">
                                    <option value="0" selected>...</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="deadline">Deadline:</label>
                                <input type="text" class="form-control" name="deadline" id="datepicker" placeholder="MM/DD/YYYY">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @else
        <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="creatSchoolwork" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-danger" id="createSchoolwork">No Subjects Yet!</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>You have to create first a subject in the Subject Management page to classify your schoolwork.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <a href="/subjects" class="btn btn-primary">Okay</a>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if(count($schoolworks) > 0)
        <div class="mt-4">
            <table class="table">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">Description</th>
                        <th scope="col">Subject</th>
                        <th scope="col">Deadline</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $currentDate = date('m/d/Y');
                    @endphp
                    @foreach($schoolworks as $schoolwork) 
                        @if($currentDate > $schoolwork->deadline)
                        <tr class="table-danger text-danger">
                        @else
                        <tr>
                        @endif
                            <td>{{ $schoolwork->description }}</td>
                            <td>@foreach($subjects as $subject)
                                    @if($schoolwork->subject_id == $subject->id)
                                        {{ $subject->name }}
                                    @endif
                                @endforeach
                            </td>
                            <td >{{ $schoolwork->deadline }}</td>
                            <td>{{ $schoolwork->status }}</td>
                            <td>
                                <form class="d-inline" action="/schoolworks/{{ $schoolwork->id }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-info" type="submit">Submit</button>
                                </form>
                                <form class="d-inline" action="/schoolworks/{{ $schoolwork->id }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <a class="btn btn-danger text-white" type="button" data-toggle="modal" data-target="#deleteModal">Delete</a>
                                    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title text-danger" id="deleteModalLabel">You are trying to delete a schoolwork!</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>You haven't submitted this schoolwork yet.</p>
                                                <p>Are you sure you want to delete this item?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
        @if(count($submittedworks) > 0)
            <div class="mt-3">
                <h3 class="mt-2 pt-3 border border-top-primary border-right-0 border-bottom-0 border-left-0">Submitted Schoolworks</h3>
                <table class="table">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">Description</th>
                            <th scope="col">Subject</th>
                            <th scope="col">Date Submitted</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($submittedworks as $submittedwork)
                        <tr>
                            <td>{{ $submittedwork->description }}</td>
                            <td>@foreach($subjects as $subject)
                                    @if($submittedwork->subject_id == $subject->id)
                                        {{ $subject->name }}
                                    @endif
                                @endforeach
                            </td>
                            <td>{{ $submittedwork->date_submitted }}</td>
                            <td>{{ $submittedwork->status }}</td>
                            <td>
                                <form class="d-inline" action="/schoolworks/{{ $submittedwork->id }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
@section('script')
<script>
    $( function() {
        $( "#datepicker" ).datepicker({
            minDate: new Date()
        });
    });
    document.addEventListener("click", function(){
        document.getElementById("alert").style.display = "none";
    });
</script>
@endsection  