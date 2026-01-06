@extends('layout')

@section('content')
  <div class="min-h-screen bg-gray-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">

      {{-- Success / Error Messages --}}
      @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
          {{ session('success') }}
        </div>
      @endif

      @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
          <ul class="list-disc pl-5">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      {{-- Upload Card --}}
      <div class="bg-white shadow-md rounded-lg p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
          Import Students & Schools
        </h2>

        <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
          @csrf

          <div>
            <label for="file" class="block text-sm font-medium text-gray-700 mb-2">
              Upload File
            </label>
            <input type="file" name="file" id="file"
              class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
              required>
            <p class="mt-2 text-sm text-gray-500">
              Supported formats: .csv
            </p>
          </div>

          <button type="submit"
            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            Upload & Import
          </button>
        </form>
      </div>
    </div>
  </div>
@endsection