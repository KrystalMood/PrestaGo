@extends('admin.layouts.app')

@section('title', 'Manage Skills for ' . $subCompetition->name)

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">{{ $subCompetition->name }} - Manage Skills</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.competitions.index') }}">Competitions</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.competitions.sub-competitions.index', $competition->id) }}">{{ $competition->name }}</a></li>
        <li class="breadcrumb-item active">{{ $subCompetition->name }} - Skills</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-table me-1"></i>
                Skills Required for {{ $subCompetition->name }}
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSkillModal">
                <i class="fas fa-plus"></i> Add Skill
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="skillsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Importance Level</th>
                            <th>Weight Value</th>
                            <th>Criterion Type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subCompetition->skills as $skill)
                        <tr>
                            <td>{{ $skill->id }}</td>
                            <td>{{ $skill->name }}</td>
                            <td>{{ $skill->category }}</td>
                            <td>{{ $skill->pivot->importance_level }}</td>
                            <td>{{ $skill->pivot->weight_value }}</td>
                            <td>{{ ucfirst($skill->pivot->criterion_type) }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary edit-skill-btn" 
                                    data-skill-id="{{ $skill->id }}" 
                                    data-skill-name="{{ $skill->name }}"
                                    data-importance-level="{{ $skill->pivot->importance_level }}"
                                    data-weight-value="{{ $skill->pivot->weight_value }}"
                                    data-criterion-type="{{ $skill->pivot->criterion_type }}"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editSkillModal">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger delete-skill-btn" 
                                    data-skill-id="{{ $skill->id }}" 
                                    data-skill-name="{{ $skill->name }}"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteSkillModal">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Skill Modal -->
<div class="modal fade" id="addSkillModal" tabindex="-1" aria-labelledby="addSkillModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSkillModalLabel">Add Skill to {{ $subCompetition->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addSkillForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="skill_id" class="form-label">Skill</label>
                        <select class="form-select" id="skill_id" name="skill_id" required>
                            <option value="">Select Skill</option>
                            @foreach($allSkills as $skill)
                                @if(!$subCompetition->skills->contains($skill->id))
                                <option value="{{ $skill->id }}">{{ $skill->name }} ({{ $skill->category }})</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="importance_level" class="form-label">Importance Level (1-10)</label>
                        <input type="number" class="form-control" id="importance_level" name="importance_level" min="1" max="10" required>
                    </div>
                    <div class="mb-3">
                        <label for="weight_value" class="form-label">Weight Value</label>
                        <input type="number" class="form-control" id="weight_value" name="weight_value" step="0.01" value="1.0">
                    </div>
                    <div class="mb-3">
                        <label for="criterion_type" class="form-label">Criterion Type</label>
                        <select class="form-select" id="criterion_type" name="criterion_type">
                            <option value="benefit">Benefit</option>
                            <option value="cost">Cost</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Skill</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Skill Modal -->
<div class="modal fade" id="editSkillModal" tabindex="-1" aria-labelledby="editSkillModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSkillModalLabel">Edit Skill</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editSkillForm">
                <div class="modal-body">
                    <input type="hidden" id="edit_skill_id" name="skill_id">
                    <div class="mb-3">
                        <label class="form-label">Skill Name</label>
                        <input type="text" class="form-control" id="edit_skill_name" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="edit_importance_level" class="form-label">Importance Level (1-10)</label>
                        <input type="number" class="form-control" id="edit_importance_level" name="importance_level" min="1" max="10" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_weight_value" class="form-label">Weight Value</label>
                        <input type="number" class="form-control" id="edit_weight_value" name="weight_value" step="0.01">
                    </div>
                    <div class="mb-3">
                        <label for="edit_criterion_type" class="form-label">Criterion Type</label>
                        <select class="form-select" id="edit_criterion_type" name="criterion_type">
                            <option value="benefit">Benefit</option>
                            <option value="cost">Cost</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Skill</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Skill Modal -->
<div class="modal fade" id="deleteSkillModal" tabindex="-1" aria-labelledby="deleteSkillModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteSkillModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to remove <span id="delete_skill_name"></span> from this sub-competition?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteSkill">Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#skillsTable').DataTable();
        
        // Add Skill Form Submission
        $('#addSkillForm').on('submit', function(e) {
            e.preventDefault();
            
            $.ajax({
                url: "{{ route('admin.competitions.sub-competitions.skills.store', ['competition' => $competition->id, 'sub_competition' => $subCompetition->id]) }}",
                method: 'POST',
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $('#addSkillModal').modal('hide');
                        toastr.success(response.message);
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        toastr.error(xhr.responseJSON.message);
                    } else {
                        toastr.error('An error occurred. Please try again.');
                    }
                }
            });
        });
        
        // Edit Skill Button Click
        $('.edit-skill-btn').on('click', function() {
            var skillId = $(this).data('skill-id');
            var skillName = $(this).data('skill-name');
            var importanceLevel = $(this).data('importance-level');
            var weightValue = $(this).data('weight-value');
            var criterionType = $(this).data('criterion-type');
            
            $('#edit_skill_id').val(skillId);
            $('#edit_skill_name').val(skillName);
            $('#edit_importance_level').val(importanceLevel);
            $('#edit_weight_value').val(weightValue);
            $('#edit_criterion_type').val(criterionType);
        });
        
        // Edit Skill Form Submission
        $('#editSkillForm').on('submit', function(e) {
            e.preventDefault();
            
            var skillId = $('#edit_skill_id').val();
            
            $.ajax({
                url: "{{ route('admin.competitions.sub-competitions.skills.update', ['competition' => $competition->id, 'sub_competition' => $subCompetition->id, 'skill' => ':skillId']) }}".replace(':skillId', skillId),
                method: 'PUT',
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $('#editSkillModal').modal('hide');
                        toastr.success(response.message);
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    }
                },
                error: function() {
                    toastr.error('An error occurred. Please try again.');
                }
            });
        });
        
        // Delete Skill Button Click
        $('.delete-skill-btn').on('click', function() {
            var skillId = $(this).data('skill-id');
            var skillName = $(this).data('skill-name');
            
            $('#delete_skill_name').text(skillName);
            $('#confirmDeleteSkill').data('skill-id', skillId);
        });
        
        // Confirm Delete Skill
        $('#confirmDeleteSkill').on('click', function() {
            var skillId = $(this).data('skill-id');
            
            $.ajax({
                url: "{{ route('admin.competitions.sub-competitions.skills.destroy', ['competition' => $competition->id, 'sub_competition' => $subCompetition->id, 'skill' => ':skillId']) }}".replace(':skillId', skillId),
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $('#deleteSkillModal').modal('hide');
                        toastr.success(response.message);
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    }
                },
                error: function() {
                    toastr.error('An error occurred. Please try again.');
                }
            });
        });
    });
</script>
@endsection
