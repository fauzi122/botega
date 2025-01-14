<div>
    <div wire:ignore.self id="modalform" class="modal fade" tabindex="-1" role="dialog">
        <!-- Konten Modal -->
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $editform ? 'Edit Redeem Point' : 'Tambah Redeem Point' }}</h5>
                    <button type="button" class="close btn btn-danger" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Notifikasi Sukses/Error -->
                    @if(session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    @if(session()->has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <form id="form_data" wire:submit.prevent>
                        <!-- Pilihan Member -->
                        <div class="form-group mb-3">
                            <div wire:ignore>
                                <label for="user_id">Member</label>
                                <select class="select2bind"
                                    name="user_id"
                                    data-url="{{url('admin/member/select2')}}"
                                    data-parent="#modalform"
                                    {{ $editform ? 'disabled' : '' }}>
                                    @if($editform)
                                    <option value="{{ $user_id }}">{{ $member }}</option>
                                    @endif
                                </select>

                            </div>
                            <span>Poin Member: {{ $point ?? '' }}</span>
                            @error('user_id')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        <!-- Pilihan Reward -->
                        <div class="form-group mb-3">
                            <div wire:ignore>
                                <label for="reward_id">Reward</label>
                                <select class="select2bind"
                                    name="reward_id"
                                    wire:model="reward_id"
                                    data-url="{{ url('admin/reward/select2') }}"
                                    data-parent="#modalform">
                                </select>
                            </div>
                            <span>Poin yang Dibutuhkan: {{ $required_points }}</span><br />
                            @if(!$editform)
                            <span>Sisa Poin Member: {{ $remaining_points }}</span>
                            @endif
                            @error('reward_id')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        <!-- Notes -->
                        <div class="form-group mb-3">
                            <label for="notes">Notes</label>
                            <textarea id="notes" name="notes"
                                class="form-control @error('notes') is-invalid @enderror"
                                wire:model="notes"></textarea>
                            @error('notes')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <!-- Tombol Footer Modal -->
                    <button type="button" class="btn btn-secondary" wire:click="resetState" data-bs-dismiss="modal">Close</button>
                    <button wire:loading.attr="disabled" onclick="save()" type="button" class="btn btn-primary">
                        {{ $editform ? 'Simpan Perubahan' : 'Simpan Baru' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Function untuk mengambil poin member
    async function updateMemberPoints(userId) {
        if (!userId) {
            document.getElementById('member-points').textContent = '-';
            document.getElementById('remaining-points').textContent = '-';
            return;
        }

        try {
            const response = await fetch(`{{ url('admin/member/get-points') }}/${userId}`);
            const data = await response.json();
            document.getElementById('member-points').textContent = data.points ?? '-';
            updateRemainingPoints();
        } catch (error) {
            console.error('Error fetching member points:', error);
        }
    }

    // Function untuk mengambil poin reward
    async function updateRewardPoints(rewardId) {
        if (!rewardId) {
            document.getElementById('reward-points').textContent = '-';
            document.getElementById('remaining-points').textContent = '-';
            return;
        }

        try {
            const response = await fetch(`{{ url('admin/reward/get-points') }}/${rewardId}`);
            const data = await response.json();
            document.getElementById('reward-points').textContent = data.points ?? '-';
            updateRemainingPoints();
        } catch (error) {
            console.error('Error fetching reward points:', error);
        }
    }

    // Fungsi untuk memperbarui sisa poin secara dinamis
    function updateRemainingPoints() {
        const memberPointsElement = document.getElementById('member-points');
        const rewardPointsElement = document.getElementById('reward-points');
        const remainingPointsElement = document.getElementById('remaining-points');

        // Ambil nilai poin member dan reward, default ke 0 jika tidak ada nilai
        const memberPoints = parseFloat(memberPointsElement.textContent) || 0;
        const rewardPoints = parseFloat(rewardPointsElement.textContent) || 0;

        // Hitung sisa poin
        const remainingPoints = memberPoints - rewardPoints;

        // Tampilkan pesan sesuai kondisi
        if (remainingPoints >= 0) {
            remainingPointsElement.textContent = `${remainingPoints} Poin Tersedia`;
            remainingPointsElement.classList.remove('text-danger');
            remainingPointsElement.classList.add('text-success');
        } else {
            remainingPointsElement.textContent = 'Poin Tidak Cukup';
            remainingPointsElement.classList.remove('text-success');
            remainingPointsElement.classList.add('text-danger');
        }
    }
</script>