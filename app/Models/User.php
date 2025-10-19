<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nip',
        'role',
        'student_id',
        'teacher_id',
        'class_level',
        'class_section',
        'phone',
        'address',
        'date_of_birth',
        'gender',
        'religion',
        'previous_school',
        'previous_school_address',
        'graduation_year',
        'transfer_reason',
        'blood_type',
        'allergies',
        'medical_conditions',
        'parent_name',
        'parent_phone',
        'parent_email',
        'is_active',
        // Teacher fields
        'subject',
        'position',
        'employment_status',
        'join_date',
        'education',
        'certification',
        'experience_years',
        'bio',
        'photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
            'join_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function courses()
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }

    public function enrollments()
    {
        return $this->hasMany(CourseEnrollment::class, 'student_id');
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class, 'student_id');
    }

    public function forumPosts()
    {
        return $this->hasMany(Forum::class, 'author_id');
    }

    public function forumReplies()
    {
        return $this->hasMany(ForumReply::class);
    }

    // Scopes
    public function scopeStudents($query)
    {
        return $query->where('role', 'student');
    }

    public function scopeTeachers($query)
    {
        return $query->where('role', 'teacher');
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Accessors
    public function getRoleLabelAttribute()
    {
        $roles = [
            'admin' => 'Administrator',
            'teacher' => 'Guru',
            'student' => 'Siswa'
        ];
        
        return $roles[$this->role] ?? ucfirst($this->role);
    }

    public function getIsStudentAttribute()
    {
        return $this->role === 'student';
    }

    public function getIsTeacherAttribute()
    {
        return $this->role === 'teacher';
    }

    public function getPhotoUrlAttribute()
    {
        if (!$this->photo) {
            // Return default image based on role
            if ($this->role === 'student') {
                return asset('images/default-student.png');
            } elseif ($this->role === 'teacher') {
                return asset('images/default-teacher.png');
            } else {
                return asset('images/default-user.png');
            }
        }
        
        if (filter_var($this->photo, FILTER_VALIDATE_URL)) {
            return $this->photo;
        }
        
        if (str_starts_with($this->photo, 'http://') || str_starts_with($this->photo, 'https://')) {
            return $this->photo;
        }
        
        // Fallback uploads -> storage for hosting
        if (str_starts_with($this->photo, 'uploads/students/photos/')) {
            return asset(str_replace('uploads/students/photos/', 'storage/students/photos/', $this->photo));
        }
        if (str_starts_with($this->photo, 'uploads/students/')) {
            return asset(str_replace('uploads/students/', 'storage/students/', $this->photo));
        }
        if (str_starts_with($this->photo, 'uploads/teachers/photos/')) {
            return asset(str_replace('uploads/teachers/photos/', 'storage/teachers/photos/', $this->photo));
        }
        if (str_starts_with($this->photo, 'uploads/teachers/')) {
            return asset(str_replace('uploads/teachers/', 'storage/teachers/', $this->photo));
        }
        if (str_starts_with($this->photo, 'uploads/users/')) {
            return asset(str_replace('uploads/users/', 'storage/users/', $this->photo));
        }
        if (str_starts_with($this->photo, 'uploads/avatars/')) {
            return asset(str_replace('uploads/avatars/', 'storage/avatars/', $this->photo));
        }
        
        // If starts with public/, convert to storage/
        if (str_starts_with($this->photo, 'public/')) {
            return asset('storage/' . str_replace('public/', '', $this->photo));
        }
        
        // Check if it's a storage path with 'storage/' prefix
        if (str_starts_with($this->photo, 'storage/')) {
            return asset($this->photo);
        }
        
        // Check if it's a storage path without 'storage/' prefix
        if (str_starts_with($this->photo, 'teachers/') || str_starts_with($this->photo, 'students/')) {
            return asset('storage/' . $this->photo);
        }
        
        // Check if it's just a filename (old format)
        if (!str_contains($this->photo, '/')) {
            if ($this->role === 'student') {
                return asset('storage/students/photos/' . $this->photo);
            } elseif ($this->role === 'teacher') {
                return asset('storage/teachers/' . $this->photo);
            } else {
                return asset('storage/' . $this->photo);
            }
        }
        
        // If it's already a full URL or path
        return $this->photo;
    }

    public function getIsAdminAttribute()
    {
        return $this->role === 'admin';
    }
}
