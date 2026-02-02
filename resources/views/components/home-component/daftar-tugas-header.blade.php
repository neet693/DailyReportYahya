{{-- Header: Profil & unit --}}
                                <div class="hstack gap-2">
                                    <div class="p-2">
                                        <img src="{{ $data->profile_image ? asset('profile_images/' . $data->profile_image) : asset('asset/default_profile.jpg') }}"
                                            class="rounded-circle" width="100" height="100" alt="Foto">
                                    </div>
                                    <div class="p-2">
                                        <a href="{{ route('employment-detail.show', $data->employmentDetail) }}"
                                            class="text-decoration-none text-dark">
                                            <div class="fw-semibold small mb-0">{{ $data->name }}</div>
                                        </a>
                                        <small class="text-muted d-block mb-1">{{ $data->jobdesk->title ?? '-' }}
                                        </small>
                                        {{-- Dropdown ganti unit jika user sedang login --}}
                                        @if ($data->id === auth()->id())
                                            <form action="{{ route('switchUnit') }}" method="POST">
                                                @csrf
                                                <select name="unit_id" onchange="this.form.submit()"
                                                    class="form-select form-select-sm border-0"
                                                    style="background-color: rgba(244, 248, 225, 0.9); font-size: 0.75rem; width: 140px; padding: 2px 6px; border-radius: 0.3rem; box-shadow: none;">
                                                    @foreach (auth()->user()->units as $unit)
                                                        <option value="{{ $unit->id }}"
                                                            {{ session('active_unit_id') == $unit->id ? 'selected' : '' }}>
                                                            {{ $unit->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </form>
                                        @endif
                                    </div>
                                </div>
