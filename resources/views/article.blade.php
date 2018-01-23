@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">

            <!-- Display Validation Errors -->
            <!-- resources/views/common/errors.blade.php -->


             <div class="breadcrumb">
                
                <a href="/">← back to overview</a>

            </div>
            
            <div class="panel panel-default">
                <div class="panel-heading"><?php echo $title; ?> article</div>

                <div class="panel-content">
                    
                    

                    <!-- New Task Form -->
                    <form action="/article/add" method="POST" class="form-horizontal">
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <!-- Article data -->
                        <div class="form-group">
                            <label for="article-title" class="col-sm-3 control-label">Title (max. 255 characters)</label>

                            <div class="col-sm-6">
                                <input type="text" name="title" id="article-title" class="form-control" value="<?php if($title == 'Edit') {echo $titleValue;}?>">
                            </div>
                        </div>

                        
                        <!-- Article url -->
                        <div class="form-group">
                            <label for="article-url" class="col-sm-3 control-label">URL</label>

                            <div class="col-sm-6">
                                <input type="text" name="url" id="article-url" class="form-control" value="<?php if($title == 'Edit') {echo $urlValue;}?>">
                            </div>
                        </div>


                        <!-- Add Article Button -->
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-6">
                                <button type="submit" class="btn btn-default">
                                    <?php echo $title;?> Article
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection